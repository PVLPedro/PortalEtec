<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        $etecIds = auth()->user()->etecs()->pluck('etecs.id');

        $schoolClasses = SchoolClass::whereIn('etec_id', $etecIds)
            ->withCount('users')
            ->orderBy('serie')
            ->get();

        return view('school-classes.index', compact('schoolClasses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'curso' => ['required', 'string', 'max:255'],
            'serie' => ['required', 'string', 'max:50'],
            'turno' => ['required', 'string', 'max:50'],
        ]);

        $etecIds = auth()->user()->etecs()->pluck('etecs.id');

        SchoolClass::create([...$validated, 'etec_id' => $etecIds->first()]);

        return back()->with('status', 'Turma criada!');
    }

    public function show(SchoolClass $schoolClass)
    {
        $this->authorizeClass($schoolClass);

        $schoolClass->load('users');

        return view('school-classes.show', ['schoolClass' => $schoolClass]);
    }

    public function update(Request $request, SchoolClass $schoolClass)
    {
        $this->authorizeClass($schoolClass);

        $validated = $request->validate([
            'curso' => ['required', 'string', 'max:255'],
            'serie' => ['required', 'string', 'max:50'],
            'turno' => ['required', 'string', 'max:50'],
        ]);

        $schoolClass->update($validated);

        return back()->with('status', 'Turma atualizada!');
    }

    public function destroy(SchoolClass $schoolClass)
    {
        $this->authorizeClass($schoolClass);

        $schoolClass->delete();

        return redirect()->route('school-classes.index')->with('status', 'Turma removida!');
    }

    public function removeUser(SchoolClass $schoolClass, User $user)
    {
        $this->authorizeClass($schoolClass);

        $schoolClass->users()->detach($user->id);

        return back()->with('status', 'Usuário removido da turma!');
    }

    private function authorizeClass(SchoolClass $schoolClass): void
    {
        $etecIds = auth()->user()->etecs()->pluck('etecs.id');

        abort_unless($etecIds->contains($schoolClass->etec_id), 403);
    }
}
