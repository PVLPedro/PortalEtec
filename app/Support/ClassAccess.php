<?php

namespace App\Support;

use App\Models\Activity;
use App\Models\SchoolClass;
use App\Models\SchoolClassDiscipline;
use App\Models\Side;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ClassAccess
{
    public const ALL_SIDES_NAME = 'Composto';

    public static function inEtec(User $user, SchoolClass $schoolClass): bool
    {
        return $user->etecs()->where('etecs.id', $schoolClass->etec_id)->exists();
    }

    public static function isMember(User $user, SchoolClass $schoolClass): bool
    {
        if ($user->isStudent()) {
            return (int) $user->student?->school_class_id === (int) $schoolClass->id;
        }

        return self::inEtec($user, $schoolClass);
    }

    public static function teaches(
        User $user,
        SchoolClass $schoolClass,
        ?int $disciplineId = null,
    ): bool {
        return DB::table('school_class_teacher')
            ->where('school_class_id', $schoolClass->id)
            ->where('teacher_id', $user->id)
            ->when($disciplineId, fn($query) => $query->where('discipline_id', $disciplineId))
            ->exists();
    }

    public static function canManageDisciplines(User $user, SchoolClass $schoolClass): bool
    {
        return $user->isCoordinator() && self::inEtec($user, $schoolClass);
    }

    public static function canManageAnnouncements(User $user, SchoolClass $schoolClass): bool
    {
        if (!self::inEtec($user, $schoolClass)) {
            return false;
        }

        if ($user->isCoordinator()) {
            return true;
        }

        return $user->isTeacher() && self::teaches($user, $schoolClass);
    }

    public static function manageableClassDisciplines(User $user, SchoolClass $schoolClass): Collection
    {
        if ($user->isStudent() || !self::inEtec($user, $schoolClass)) {
            return collect();
        }

        $query = SchoolClassDiscipline::where('school_class_id', $schoolClass->id)->with([
            'discipline',
            'color',
        ]);

        if (!$user->isCoordinator()) {
            $query->whereIn(
                'discipline_id',
                DB::table('school_class_teacher')
                    ->where('school_class_id', $schoolClass->id)
                    ->where('teacher_id', $user->id)
                    ->pluck('discipline_id'),
            );
        }

        return $query
            ->get()
            ->sortBy(fn($item) => $item->discipline->name)
            ->values();
    }

    public static function canViewActivity(User $user, Activity $activity): bool
    {
        $schoolClass = $activity->schoolClassDiscipline->schoolClass;

        if (!self::isMember($user, $schoolClass)) {
            return false;
        }

        if (!$user->isStudent() || $activity->side_id === null) {
            return true;
        }

        return (int) $user->student?->side_id === (int) $activity->side_id;
    }

    public static function assignableSides(): Collection
    {
        return Side::where('name', '!=', self::ALL_SIDES_NAME)->orderBy('name')->get();
    }
}
