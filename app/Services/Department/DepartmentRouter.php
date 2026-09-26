<?php

namespace App\Services\Department;

use App\Models\Staff;

class DepartmentRouter
{
    /**
     * Route map: department role → landing route name.
     */
    public const ROUTES = [
        "academic"       => "teacher.dashboard",
        "class_teacher"  => "class-teacher.dashboard",
        "duty"           => "duty.dashboard",
        "health"         => "department.health",
        "discipline"     => "department.discipline",
        "sports"         => "department.sports",
        "boarding"       => "department.boarding",
        "feeding"        => "department.feeding",
        "library"        => "department.library",
        "guidance"       => "department.guidance",
        "environment"    => "department.environment",
        "security"       => "department.security",
        "finance"        => "admin.dashboard",
        "administration" => "admin.dashboard",
    ];

    /**
     * Determine the landing route for a staff based on their department_roles.
     * - No roles → dashboard
     * - One role → that department
     * - Multiple → hub page
     */
    public static function landingRoute(?Staff $staff): string
    {
        if (!$staff) return "dashboard";

        $roles = $staff->roleList();
        if (empty($roles)) return "dashboard";

        if (count($roles) > 1) return "department.hub";

        return self::ROUTES[$roles[0]] ?? "dashboard";
    }

    /**
     * Cards for hub page.
     */
    public static function cards(?Staff $staff): array
    {
        if (!$staff) return [];

        $meta = [
            "academic"      => ["title" => "Academic",          "desc" => "Teaching, lesson plans, marks",         "route" => "teacher.dashboard",            "color" => "blue"],
            "class_teacher" => ["title" => "Class Teacher",     "desc" => "My class, discipline, welfare",        "route" => "class-teacher.dashboard",      "color" => "green"],
            "duty"          => ["title" => "Teacher on Duty",   "desc" => "Duty roster, supervision",             "route" => "duty.dashboard",               "color" => "yellow"],
            "health"        => ["title" => "Health",            "desc" => "Sick students, first aid",              "route" => "department.health",            "color" => "red"],
            "discipline"    => ["title" => "Discipline",        "desc" => "Behavior, warnings",                    "route" => "department.discipline",        "color" => "orange"],
            "sports"        => ["title" => "Sports",            "desc" => "Teams, matches, training",              "route" => "department.sports",            "color" => "green"],
            "boarding"      => ["title" => "Boarding",          "desc" => "Dorms, night duty",                     "route" => "department.boarding",          "color" => "purple"],
            "feeding"       => ["title" => "Feeding",           "desc" => "Meals, hygiene",                        "route" => "department.feeding",           "color" => "orange"],
            "library"       => ["title" => "Library",           "desc" => "Books, loans",                          "route" => "department.library",           "color" => "blue"],
            "guidance"      => ["title" => "Guidance",          "desc" => "Counseling, career",                    "route" => "department.guidance",          "color" => "indigo"],
            "environment"   => ["title" => "Environment",       "desc" => "Cleanliness, tree planting",            "route" => "department.environment",       "color" => "teal"],
            "security"      => ["title" => "Security",          "desc" => "Patrol, gate duty",                     "route" => "department.security",          "color" => "gray"],
            "finance"       => ["title" => "Finance",           "desc" => "Fees, invoices, payments",              "route" => "admin.dashboard",              "color" => "blue"],
            "administration"=> ["title" => "Administration",    "desc" => "School-wide tools",                     "route" => "admin.dashboard",              "color" => "blue"],
        ];

        $cards = [];
        foreach ($staff->roleList() as $role) {
            if (isset($meta[$role])) {
                $cards[] = array_merge(["key" => $role], $meta[$role]);
            }
        }

        return $cards;
    }

    /**
     * Map a single department role to a Spatie role for permissions.
     */
    public static function spatieRole(string $deptRole): string
    {
        return match ($deptRole) {
            "academic", "class_teacher", "health", "discipline", "sports",
            "boarding", "feeding", "library", "guidance", "environment", "security" => "teacher",
            "duty"           => "teacher_on_duty",
            "finance"        => "bursar",
            "administration" => "admin",
            default          => "staff",
        };
    }
}