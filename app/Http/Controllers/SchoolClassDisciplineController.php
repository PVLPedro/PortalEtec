<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Controllers\Concerns\ConfirmsPassword;
use App\Models\ActivityFile;
use App\Models\Color;
use App\Models\Discipline;
use App\Models\SchoolClass;
use App\Models\SchoolClassDiscipline;
use App\Models\User;
use App\Support\ClassAccess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SchoolClassDisciplineController extends Controller
{
    use ConfirmsPassword;

    private const MAX_TEACHERS = 3;

    public function index(SchoolClass $schoolClass): RedirectResponse
    {
        return redirect()->route('school-class.show', [$schoolClass, 'secao' => 'disciplinas']);
    }

    public function create(Request $request, SchoolClass $schoolClass): View
    {
        $this->authorizeManage($request, $schoolClass);

        return view('class-content.disciplines.form', $this->formData($schoolClass, null));
    }

    public function store(Request $request, SchoolClass $schoolClass): RedirectResponse
    {
        $this->authorizeManage($request, $schoolClass);

        $validated = $request->validate(
            [
                'discipline_id' => [
                    'required',
                    'exists:disciplines,id',
                    Rule::unique('school_class_discipline', 'discipline_id')->where(
                        'school_class_id',
                        $schoolClass->id,
                    ),
                ],
                'color_id' => ['nullable', 'exists:colors,id'],
                ...$this->teacherRules($schoolClass),
            ],
            ['discipline_id.unique' => 'Esta disciplina já foi adicionada à turma.'],
        );

        $discipline = Discipline::findOrFail($validated['discipline_id']);
        $colorId = $this->resolveColor($discipline, $validated['color_id'] ?? null);
        $teachers = $this->cleanTeachers($validated['teachers'] ?? []);

        DB::transaction(function () use ($schoolClass, $discipline, $colorId, $teachers) {
            SchoolClassDiscipline::create([
                'school_class_id' => $schoolClass->id,
                'discipline_id' => $discipline->id,
                'color_id' => $colorId,
            ]);

            $this->syncTeachers($schoolClass->id, $discipline->id, $teachers);
        });

        return redirect()
            ->route('school-class.show', [$schoolClass, 'secao' => 'disciplinas'])
            ->with('status', 'Disciplina adicionada à turma!');
    }

    public function edit(
        Request $request,
        SchoolClass $schoolClass,
        SchoolClassDiscipline $classDiscipline,
    ): View {
        $this->authorizeManage($request, $schoolClass);
        $this->ensureBelongs($schoolClass, $classDiscipline);

        return view('class-content.disciplines.form', $this->formData($schoolClass, $classDiscipline));
    }

    public function update(
        Request $request,
        SchoolClass $schoolClass,
        SchoolClassDiscipline $classDiscipline,
    ): RedirectResponse {
        $this->authorizeManage($request, $schoolClass);
        $this->ensureBelongs($schoolClass, $classDiscipline);

        $validated = $request->validate([
            'color_id' => ['nullable', 'exists:colors,id'],
            ...$this->teacherRules($schoolClass),
        ]);

        $classDiscipline->loadMissing('discipline');

        $colorId = $this->resolveColor($classDiscipline->discipline, $validated['color_id'] ?? null);
        $teachers = $this->cleanTeachers($validated['teachers'] ?? []);

        DB::transaction(function () use ($schoolClass, $classDiscipline, $colorId, $teachers) {
            $classDiscipline->update(['color_id' => $colorId]);

            $this->syncTeachers($schoolClass->id, $classDiscipline->discipline_id, $teachers);
        });

        return redirect()
            ->route('school-class.show', [$schoolClass, 'secao' => 'disciplinas'])
            ->with('status', 'Disciplina atualizada!');
    }

    public function destroy(
        Request $request,
        SchoolClass $schoolClass,
        SchoolClassDiscipline $classDiscipline,
    ): RedirectResponse {
        $this->authorizeManage($request, $schoolClass);
        $this->ensureBelongs($schoolClass, $classDiscipline);
        $this->confirmPassword($request);

        $paths = ActivityFile::pathsWhereActivity(
            fn ($query) => $query->where('school_class_discipline_id', $classDiscipline->id),
        );

        DB::transaction(function () use ($schoolClass, $classDiscipline) {
            DB::table('school_class_teacher')
                ->where('school_class_id', $schoolClass->id)
                ->where('discipline_id', $classDiscipline->discipline_id)
                ->delete();

            $classDiscipline->delete();
        });

        Storage::disk(ActivityFile::DISK)->delete($paths);

        return redirect()
            ->route('school-class.show', [$schoolClass, 'secao' => 'disciplinas'])
            ->with('status', 'Disciplina removida da turma!');
    }

    private function authorizeManage(Request $request, SchoolClass $schoolClass): void
    {
        abort_unless(ClassAccess::canManageDisciplines($request->user(), $schoolClass), 403);
    }

    private function ensureBelongs(SchoolClass $schoolClass, SchoolClassDiscipline $classDiscipline): void
    {
        abort_unless((int) $classDiscipline->school_class_id === (int) $schoolClass->id, 404);
    }

    private function formData(SchoolClass $schoolClass, ?SchoolClassDiscipline $classDiscipline): array
    {
        $schoolClass->loadMissing(['course', 'grade', 'shift']);
        $classDiscipline?->loadMissing('discipline');

        $used = SchoolClassDiscipline::where('school_class_id', $schoolClass->id)->pluck(
            'discipline_id',
        );

        return [
            'schoolClass' => $schoolClass,
            'classDiscipline' => $classDiscipline,
            'disciplines' => $classDiscipline
                ? collect()
                : Discipline::whereNotIn('id', $used)->orderBy('name')->get(),
            'courseDisciplineIds' => Discipline::whereNotNull('course_id')->pluck('id')->all(),
            'colors' => Color::orderBy('name')->get(),
            'availableTeachers' => $this->availableTeachers($schoolClass),
            'sides' => ClassAccess::assignableSides(),
            'assigned' => $classDiscipline
                ? $classDiscipline
                    ->teachers()
                    ->get()
                    ->map(fn ($teacher) => ['id' => $teacher->id, 'side_id' => $teacher->side_id])
                    ->values()
                    ->all()
                : [],
            'maxTeachers' => self::MAX_TEACHERS,
        ];
    }

    private function availableTeachers(SchoolClass $schoolClass): Collection
    {
        return User::where('role', Role::Professor->value)
            ->whereHas('etecs', fn ($query) => $query->where('etecs.id', $schoolClass->etec_id))
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function teacherRules(SchoolClass $schoolClass): array
    {
        return [
            'teachers' => ['nullable', 'array', 'max:'.self::MAX_TEACHERS],
            'teachers.*.id' => [
                'nullable',
                'distinct',
                Rule::in($this->availableTeachers($schoolClass)->pluck('id')->all()),
            ],
            'teachers.*.side_id' => [
                'nullable',
                Rule::in(ClassAccess::assignableSides()->pluck('id')->all()),
            ],
        ];
    }

    private function cleanTeachers(array $rows): Collection
    {
        $teachers = collect($rows)
            ->filter(fn ($row) => ! empty($row['id']))
            ->map(
                fn ($row) => [
                    'id' => (int) $row['id'],
                    'side_id' => ! empty($row['side_id']) ? (int) $row['side_id'] : null,
                ],
            )
            ->values();

        abort_if($teachers->count() > self::MAX_TEACHERS, 422);

        return $teachers;
    }

    private function resolveColor(Discipline $discipline, ?int $requested): int
    {
        if ($discipline->course_id !== null) {
            return (int) Color::where('code', 'gray')->value('id');
        }

        if (! $requested) {
            throw ValidationException::withMessages([
                'color_id' => 'Escolha uma cor para a disciplina.',
            ]);
        }

        return $requested;
    }

    private function syncTeachers(int $schoolClassId, int $disciplineId, Collection $teachers): void
    {
        DB::table('school_class_teacher')
            ->where('school_class_id', $schoolClassId)
            ->where('discipline_id', $disciplineId)
            ->delete();

        $now = now();

        $rows = $teachers
            ->map(
                fn ($teacher) => [
                    'school_class_id' => $schoolClassId,
                    'teacher_id' => $teacher['id'],
                    'discipline_id' => $disciplineId,
                    'side_id' => $teacher['side_id'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            )
            ->all();

        if ($rows) {
            DB::table('school_class_teacher')->insert($rows);
        }
    }
}
