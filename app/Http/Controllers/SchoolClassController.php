<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\User;
use App\Models\Etec;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Shift;
use App\Models\Color;
use App\Models\Icon;
use App\Models\Side;
use App\Models\UserStudent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SchoolClassController extends Controller
{
    public function index()
    {
        $etecIds = auth()->user()->etecs()->pluck('etecs.id');

        $schoolClasses = SchoolClass::whereIn('etec_id', $etecIds)
            ->with(['course', 'grade', 'shift', 'color'])
            ->withCount('students')
            ->join('grades', 'school_classes.grade_id', '=', 'grades.id')
            ->orderBy('grades.name')
            ->addSelect('school_classes.*')
            ->get();

        return view('school-classes.index', [
            'schoolClasses' => $schoolClasses,
            'courses' => Course::all(),
            'grades' => Grade::all(),
            'shifts' => Shift::all(),
            'colors' => Color::all(),
        ]);
    }

    public function create(Request $request)
    {
        $etecIds = auth()->user()->etecs()->pluck('etecs.id');

        $preselectedUsers = User::whereIn('id', $request->query('usuarios', []))
            ->whereHas('etecs', fn($q) => $q->whereIn('etecs.id', $etecIds))
            ->get(['id', 'name', 'role']);

        return view('school-classes.create', [
            'courses' => Course::all(),
            'grades' => Grade::all(),
            'shifts' => Shift::all(),
            'colors' => Color::all(),
            'icons' => Icon::all(),
            'preselectedUsers' => $preselectedUsers,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'grade_id' => ['required', 'exists:grades,id'],
            'shift_id' => ['required', 'exists:shifts,id'],
            'color_id' => ['required', 'exists:colors,id'],
            'usuarios' => ['sometimes', 'array'],
            'usuarios.*' => ['exists:users,id'],
        ]);

        $etecIds = auth()->user()->etecs()->pluck('etecs.id');

        $schoolClass = SchoolClass::create([
            'course_id' => $validated['course_id'],
            'grade_id' => $validated['grade_id'],
            'shift_id' => $validated['shift_id'],
            'color_id' => $validated['color_id'],
            'etec_id' => $etecIds->first(),
        ]);

        if (!empty($validated['usuarios'])) {
            $usuarios = User::whereIn('id', $validated['usuarios'])->get();

            $professorIds = $usuarios->filter(fn($u) => $u->isTeacher())->pluck('id');
            $alunoIds = $usuarios->filter(fn($u) => $u->isStudent())->pluck('id');

            if ($professorIds->isNotEmpty()) {
                $schoolClass->teachers()->syncWithoutDetaching($professorIds);
            }

            foreach ($alunoIds as $alunoId) {
                $existing = UserStudent::where('user_id', $alunoId)->first();

                UserStudent::updateOrCreate(
                    ['user_id' => $alunoId],
                    [
                        'id_class' => $schoolClass->id,
                        'rm' => $existing->rm ?? 0,
                        'id_side' => $existing->id_side ?? null,
                    ],
                );
            }
        }

        return redirect()->route('school-classes.index')->with('status', 'Turma criada!');
    }

    public function show(SchoolClass $schoolClass)
    {
        $this->authorizeClass($schoolClass);

        $schoolClass->load([
            'students.user',
            'students.side',
            'teachers',
            'course',
            'grade',
            'shift',
        ]);

        $sides = Side::all();

        return view('school-classes.show', [
            'schoolClass' => $schoolClass,
            'courses' => Course::all(),
            'grades' => Grade::all(),
            'shifts' => Shift::all(),
            'colors' => Color::all(),
            'icons' => Icon::all(),
            'sides' => $sides,
        ]);
    }

    public function edit(SchoolClass $schoolClass)
    {
        $this->authorizeClass($schoolClass);

        return view('school-classes.edit', [
            'schoolClass' => $schoolClass,
            'courses' => Course::all(),
            'grades' => Grade::all(),
            'shifts' => Shift::all(),
            'colors' => Color::all(),
            'icons' => Icon::all(),
        ]);
    }

    public function update(Request $request, SchoolClass $schoolClass)
    {
        $this->authorizeClass($schoolClass);

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'grade_id' => ['required', 'exists:grades,id'],
            'shift_id' => ['required', 'exists:shifts,id'],
            'color_id' => ['required', 'exists:colors,id'],
        ]);

        $schoolClass->update($validated);

        return back()->with('status', 'Turma atualizada!');
    }

    public function destroy(Request $request, SchoolClass $schoolClass)
    {
        $this->authorizeClass($schoolClass);
        $this->requirePassword($request);

        $schoolClass->delete();

        return redirect()->route('school-classes.index')->with('status', 'Turma removida!');
    }

    public function removeUser(Request $request, SchoolClass $schoolClass, User $user)
    {
        $this->authorizeClass($schoolClass);
        $this->requirePassword($request);

        if ($user->isStudent()) {
            UserStudent::where('user_id', $user->id)
                ->where('id_class', $schoolClass->id)
                ->update(['id_class' => null]);
        } else {
            $schoolClass->teachers()->detach($user->id);
        }

        return back()->with('status', 'Usuário removido da turma!');
    }

    public function updateSide(Request $request, SchoolClass $schoolClass, User $user)
    {
        $this->authorizeClass($schoolClass);

        $validated = $request->validate([
            'id_side' => ['nullable', 'exists:side,id_side'],
        ]);

        $userStudent = UserStudent::where('user_id', $user->id)
            ->where('id_class', $schoolClass->id)
            ->firstOrFail();

        $userStudent->update(['id_side' => $validated['id_side']]);

        return response()->json(['message' => 'Turma atualizada!']);
    }

    private function authorizeClass(SchoolClass $schoolClass): void
    {
        $etecIds = auth()->user()->etecs()->pluck('etecs.id');

        abort_unless($etecIds->contains($schoolClass->etec_id), 403);
    }

    private function requirePassword(Request $request): void
    {
        $request->validate(
            [
                'password' => ['required', 'string'],
            ],
            [
                'password.required' => 'Please confirm your password to continue.',
            ],
        );

        if (!Hash::check($request->input('password'), Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => 'Incorrect password.',
            ]);
        }
    }
}
