<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ConfirmsPassword;
use App\Models\Activity;
use App\Models\ActivityFile;
use App\Models\SchoolClass;
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
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityController extends Controller
{
    use ConfirmsPassword;

    private const MAX_FILES = 10;

    private const MAX_FILE_KB = 20480;

    private const MIMES = 'pdf,doc,docx,xls,xlsx,ppt,pptx,odt,ods,odp,txt,csv,md,zip,rar,7z,png,jpg,jpeg,gif,webp,mp3,mp4';

    public function index(Request $request, SchoolClass $schoolClass): RedirectResponse
    {
        return redirect()->route(
            'school-class.show',
            [$schoolClass, 'secao' => 'atividades'] + $request->only('disciplina'),
        );
    }

    public function create(Request $request, SchoolClass $schoolClass): View|RedirectResponse
    {
        $manageable = ClassAccess::manageableClassDisciplines($request->user(), $schoolClass);

        abort_if($manageable->isEmpty() && ! $this->canEnter($request->user(), $schoolClass), 403);

        if ($manageable->isEmpty()) {
            return redirect()
                ->route('school-class.show', [$schoolClass, 'secao' => 'atividades'])
                ->with('status', 'Não há disciplinas da turma disponíveis para você.');
        }

        return view('class-content.activities.form', $this->formData($schoolClass, $manageable, null));
    }

    public function store(Request $request, SchoolClass $schoolClass): RedirectResponse
    {
        $user = $request->user();
        $manageable = ClassAccess::manageableClassDisciplines($user, $schoolClass);

        abort_if($manageable->isEmpty(), 403);

        $validated = $request->validate($this->rules($manageable, true));
        $files = $request->file('files', []);

        if (empty($validated['deadline']) && count($files) < 1) {
            throw ValidationException::withMessages([
                'files' => 'Um material de apoio (sem data de entrega) precisa de pelo menos um arquivo.',
            ]);
        }

        $stored = [];

        try {
            $activity = DB::transaction(function () use ($validated, $user, $files, $request, &$stored) {
                $activity = Activity::create([
                    'school_class_discipline_id' => $validated['school_class_discipline_id'],
                    'author_id' => $user->id,
                    'side_id' => $validated['side_id'] ?? null,
                    'title' => $validated['title'],
                    'description' => $validated['description'] ?? null,
                    'deadline' => $validated['deadline'] ?? null,
                    'comments_enabled' => $request->boolean('comments_enabled'),
                ]);

                $this->storeFiles($activity, $files, $stored);

                return $activity;
            });
        } catch (\Throwable $e) {
            Storage::disk(ActivityFile::DISK)->delete($stored);

            throw $e;
        }

        return redirect()
            ->route('class.activities.show', [$schoolClass, $activity])
            ->with('status', $activity->isMaterial() ? 'Material publicado!' : 'Atividade criada!');
    }

    public function show(Request $request, SchoolClass $schoolClass, Activity $activity): View
    {
        $this->ensureBelongs($schoolClass, $activity);

        abort_unless(ClassAccess::canViewActivity($request->user(), $activity), 403);

        $schoolClass->loadMissing(['course', 'grade', 'shift']);
        $activity->load(['schoolClassDiscipline.discipline', 'schoolClassDiscipline.color', 'author', 'side', 'files']);

        $manageable = ClassAccess::manageableClassDisciplines($request->user(), $schoolClass);

        return view('class-content.activities.show', [
            'schoolClass' => $schoolClass,
            'activity' => $activity,
            'canManage' => $manageable->contains('id', $activity->school_class_discipline_id),
        ]);
    }

    public function edit(Request $request, SchoolClass $schoolClass, Activity $activity): View
    {
        $manageable = $this->authorizeManage($request, $schoolClass, $activity);

        $activity->load('files');

        return view('class-content.activities.form', $this->formData($schoolClass, $manageable, $activity));
    }

    public function update(
        Request $request,
        SchoolClass $schoolClass,
        Activity $activity,
    ): RedirectResponse {
        $manageable = $this->authorizeManage($request, $schoolClass, $activity);

        $validated = $request->validate($this->rules($manageable, false));

        $removeIds = $activity
            ->files()
            ->whereIn('id', array_filter((array) $request->input('remove_files', [])))
            ->pluck('id');

        if ($removeIds->isNotEmpty()) {
            $this->confirmPassword($request);
        }

        $newFiles = $request->file('files', []);
        $remaining = $activity->files()->count() - $removeIds->count() + count($newFiles);

        if (empty($validated['deadline']) && $remaining < 1) {
            throw ValidationException::withMessages([
                'files' => 'Um material de apoio (sem data de entrega) precisa de pelo menos um arquivo.',
            ]);
        }

        $removedPaths = $activity->files()->whereIn('id', $removeIds)->pluck('path')->all();
        $stored = [];

        try {
            DB::transaction(function () use ($activity, $validated, $request, $removeIds, $newFiles, &$stored) {
                $activity->update([
                    'school_class_discipline_id' => $validated['school_class_discipline_id'],
                    'side_id' => $validated['side_id'] ?? null,
                    'title' => $validated['title'],
                    'description' => $validated['description'] ?? null,
                    'deadline' => $validated['deadline'] ?? null,
                    'comments_enabled' => $request->boolean('comments_enabled'),
                ]);

                $activity->files()->whereIn('id', $removeIds)->delete();

                $this->storeFiles($activity, $newFiles, $stored);
            });
        } catch (\Throwable $e) {
            Storage::disk(ActivityFile::DISK)->delete($stored);

            throw $e;
        }

        Storage::disk(ActivityFile::DISK)->delete($removedPaths);

        return redirect()
            ->route('class.activities.show', [$schoolClass, $activity])
            ->with('status', 'Atualizado com sucesso!');
    }

    public function destroy(
        Request $request,
        SchoolClass $schoolClass,
        Activity $activity,
    ): RedirectResponse {
        $this->authorizeManage($request, $schoolClass, $activity);
        $this->confirmPassword($request);

        $activity->destroyWithFiles();

        return redirect()
            ->route('school-class.show', [$schoolClass, 'secao' => 'atividades'])
            ->with('status', 'Removido com sucesso!');
    }

    public function download(Request $request, ActivityFile $file): StreamedResponse
    {
        $activity = $file->activity()->with('schoolClassDiscipline.schoolClass')->firstOrFail();

        abort_unless(ClassAccess::canViewActivity($request->user(), $activity), 403);
        abort_unless(Storage::disk(ActivityFile::DISK)->exists($file->path), 404);

        return Storage::disk(ActivityFile::DISK)->download($file->path, $file->original_name);
    }

    private function canEnter(User $user, SchoolClass $schoolClass): bool
    {
        return ! $user->isStudent() && ClassAccess::inEtec($user, $schoolClass);
    }

    private function authorizeManage(
        Request $request,
        SchoolClass $schoolClass,
        Activity $activity,
    ): Collection {
        $this->ensureBelongs($schoolClass, $activity);

        $manageable = ClassAccess::manageableClassDisciplines($request->user(), $schoolClass);

        abort_unless($manageable->contains('id', $activity->school_class_discipline_id), 403);

        return $manageable;
    }

    private function ensureBelongs(SchoolClass $schoolClass, Activity $activity): void
    {
        $activity->loadMissing('schoolClassDiscipline');

        abort_unless(
            (int) $activity->schoolClassDiscipline->school_class_id === (int) $schoolClass->id,
            404,
        );
    }

    private function formData(
        SchoolClass $schoolClass,
        Collection $manageable,
        ?Activity $activity,
    ): array {
        $schoolClass->loadMissing(['course', 'grade', 'shift']);

        return [
            'schoolClass' => $schoolClass,
            'activity' => $activity,
            'classDisciplines' => $manageable,
            'sides' => ClassAccess::assignableSides(),
            'maxFiles' => self::MAX_FILES,
            'maxFileMb' => self::MAX_FILE_KB / 1024,
        ];
    }

    private function rules(Collection $manageable, bool $creating): array
    {
        return [
            'school_class_discipline_id' => ['required', Rule::in($manageable->pluck('id')->all())],
            'title' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:5000'],
            'deadline' => $creating ? ['nullable', 'date', 'after:now'] : ['nullable', 'date'],
            'side_id' => ['nullable', Rule::in(ClassAccess::assignableSides()->pluck('id')->all())],
            'comments_enabled' => ['nullable', 'boolean'],
            'files' => ['nullable', 'array', 'max:'.self::MAX_FILES],
            'files.*' => ['file', 'max:'.self::MAX_FILE_KB, 'mimes:'.self::MIMES],
            'remove_files' => ['nullable', 'array'],
            'remove_files.*' => ['integer'],
        ];
    }

    private function storeFiles(Activity $activity, array $files, array &$stored): void
    {
        foreach ($files as $file) {
            $name = $file->getClientOriginalName();
            $mime = $file->getClientMimeType();
            $size = $file->getSize();

            $path = $file->store("activities/{$activity->id}", ActivityFile::DISK);
            $stored[] = $path;

            $activity->files()->create([
                'original_name' => $name,
                'path' => $path,
                'mime' => $mime,
                'size' => $size,
            ]);
        }
    }
}
