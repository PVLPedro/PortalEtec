<?php

namespace App\Support;

use App\Models\Activity;
use App\Models\Announcement;
use App\Models\SchoolClass;
use App\Models\SchoolClassDiscipline;
use App\Models\Side;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ClassContentData
{
    public static function for(User $user, SchoolClass $schoolClass, Request $request): array
    {
        $classDisciplines = SchoolClassDiscipline::where('school_class_id', $schoolClass->id)
            ->with(['discipline', 'color'])
            ->get()
            ->sortBy(fn ($item) => $item->discipline->name)
            ->values();

        return [
            'classDisciplines' => $classDisciplines,
            'disciplineTeachers' => $classDisciplines->mapWithKeys(
                fn ($item) => [$item->id => $item->teachers()->get()],
            ),
            'sideNames' => Side::pluck('name', 'id'),
            'canManageDisciplines' => ClassAccess::canManageDisciplines($user, $schoolClass),
            'announcements' => Announcement::where('school_class_id', $schoolClass->id)
                ->with('author')
                ->latest()
                ->get(),
            'canManageAnnouncements' => ClassAccess::canManageAnnouncements($user, $schoolClass),
            'activities' => self::activityQuery($user, $schoolClass, $request)
                ->whereNotNull('deadline')
                ->orderBy('deadline')
                ->get(),
            'materials' => self::activityQuery($user, $schoolClass, $request)
                ->whereNull('deadline')
                ->latest()
                ->get(),
            'manageActivityIds' => ClassAccess::manageableClassDisciplines($user, $schoolClass)
                ->pluck('id')
                ->all(),
            'selectedDiscipline' => (int) $request->query('disciplina'),
        ];
    }

    private static function activityQuery(User $user, SchoolClass $schoolClass, Request $request): Builder
    {
        return Activity::query()
            ->whereHas(
                'schoolClassDiscipline',
                fn ($query) => $query->where('school_class_id', $schoolClass->id),
            )
            ->with([
                'schoolClassDiscipline.discipline',
                'schoolClassDiscipline.color',
                'author',
                'side',
                'files',
            ])
            ->when(
                $request->filled('disciplina'),
                fn ($query) => $query->where(
                    'school_class_discipline_id',
                    (int) $request->query('disciplina'),
                ),
            )
            ->when($user->isStudent(), function ($query) use ($user) {
                $sideId = $user->student?->side_id;

                $query->where(
                    fn ($sub) => $sub
                        ->whereNull('side_id')
                        ->when($sideId, fn ($inner) => $inner->orWhere('side_id', $sideId)),
                );
            });
    }
}
