<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ConfirmsPassword;
use App\Models\Announcement;
use App\Models\SchoolClass;
use App\Support\ClassAccess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    use ConfirmsPassword;

    public function index(SchoolClass $schoolClass): RedirectResponse
    {
        return redirect()->route('school-class.show', [$schoolClass, 'secao' => 'comunicados']);
    }

    public function create(Request $request, SchoolClass $schoolClass): View
    {
        $this->authorizeManage($request, $schoolClass);

        $schoolClass->loadMissing(['course', 'grade', 'shift']);

        return view('class-content.announcements.form', [
            'schoolClass' => $schoolClass,
            'announcement' => null,
        ]);
    }

    public function store(Request $request, SchoolClass $schoolClass): RedirectResponse
    {
        $this->authorizeManage($request, $schoolClass);

        $validated = $this->validated($request);

        Announcement::create([
            'school_class_id' => $schoolClass->id,
            'author_id' => $request->user()->id,
            ...$validated,
        ]);

        return redirect()
            ->route('school-class.show', [$schoolClass, 'secao' => 'comunicados'])
            ->with('status', 'Comunicado publicado!');
    }

    public function edit(
        Request $request,
        SchoolClass $schoolClass,
        Announcement $announcement,
    ): View {
        $this->authorizeManage($request, $schoolClass);
        $this->ensureBelongs($schoolClass, $announcement);

        $schoolClass->loadMissing(['course', 'grade', 'shift']);

        return view('class-content.announcements.form', [
            'schoolClass' => $schoolClass,
            'announcement' => $announcement,
        ]);
    }

    public function update(
        Request $request,
        SchoolClass $schoolClass,
        Announcement $announcement,
    ): RedirectResponse {
        $this->authorizeManage($request, $schoolClass);
        $this->ensureBelongs($schoolClass, $announcement);

        $announcement->update($this->validated($request));

        return redirect()
            ->route('school-class.show', [$schoolClass, 'secao' => 'comunicados'])
            ->with('status', 'Comunicado atualizado!');
    }

    public function destroy(
        Request $request,
        SchoolClass $schoolClass,
        Announcement $announcement,
    ): RedirectResponse {
        $this->authorizeManage($request, $schoolClass);
        $this->ensureBelongs($schoolClass, $announcement);
        $this->confirmPassword($request);

        $announcement->delete();

        return redirect()
            ->route('school-class.show', [$schoolClass, 'secao' => 'comunicados'])
            ->with('status', 'Comunicado removido!');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string', 'max:5000'],
        ]);
    }

    private function authorizeManage(Request $request, SchoolClass $schoolClass): void
    {
        abort_unless(ClassAccess::canManageAnnouncements($request->user(), $schoolClass), 403);
    }

    private function ensureBelongs(SchoolClass $schoolClass, Announcement $announcement): void
    {
        abort_unless((int) $announcement->school_class_id === (int) $schoolClass->id, 404);
    }
}
