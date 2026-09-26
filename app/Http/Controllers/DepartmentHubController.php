<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Department;
use App\Models\Staff;
use App\Services\DepartmentStatsService;
use Illuminate\Http\Request;

class DepartmentHubController extends Controller
{
    public function index(Request $request)
    {
        $user  = $request->user();
        $staff = Staff::where("user_id", $user->id)->with(["subjects", "primaryDepartment"])->first();

        if (!$staff) {
            return redirect()->route("admin.dashboard");
        }

        $allRoles  = $staff->allRoles();
        $isTeacher = $staff->isTeacher();

        // Single-role teacher with no extras → straight to workspace
        if ($isTeacher && count($allRoles) <= 1 && !$staff->hasAnyDepartmentRole()) {
            return redirect()->route("staff.workspace");
        }

        $cards = [];

        // 1. Primary department card with LIVE COUNTS
        if ($staff->primaryDepartment) {
            $dept = $staff->primaryDepartment;
            $stats = $this->statsForDepartment($dept);

            $cards[] = [
                "title"    => $dept->name . " Department",
                "subtitle" => $isTeacher ? "Teaching" : "Non-Teaching",
                "color"    => $dept->color ?? "blue",
                "url"      => $this->departmentUrl($dept),
                "stats"    => $stats,
            ];
        }

        // 2. Every extra role the staff has, with live counts
        $roleLabels = Staff::departmentRoleOptions();
        foreach ($staff->extraRoleList() as $roleKey) {
            if (!isset($roleLabels[$roleKey])) continue;

            // Some roles map to a real department
            $dept = Department::where("code", $roleKey)->first();

            $cards[] = [
                "title"    => $roleLabels[$roleKey],
                "subtitle" => "Additional Role",
                "color"    => $dept->color ?? "indigo",
                "url"      => $this->roleUrl($roleKey, $dept),
                "stats"    => $dept ? $this->statsForDepartment($dept) : [],
            ];
        }

        return view("hub.index", compact("staff", "cards", "isTeacher"));
    }

    public function show(Request $request, string $code)
    {
        $department = Department::where("code", $code)->firstOrFail();
        $user       = $request->user();
        $staff      = Staff::where("user_id", $user->id)->first();

        // Redirect straight to the department's index if a controller exists
        $controller = $this->controllerForCode($code);
        if ($controller && class_exists($controller)) {
            return app($controller)->index($request);
        }

        $allowed = $staff && (
            optional($staff->primaryDepartment)->code === $code
            || in_array($code, $staff->roleList(), true)
            || in_array($code, $staff->extraRoleList(), true)
        );

        if (!$allowed && !$user->hasRole("admin")) {
            abort(403, "You are not assigned to this department.");
        }

        $subjects = $department->subjects()->where("is_active", true)->get();
        $stats    = DepartmentStatsService::forCode($code);

        return view("hub.department", compact("department", "staff", "subjects", "stats"));
    }

    public function role(Request $request, string $roleKey)
    {
        $roleLabels = Staff::departmentRoleOptions();
        if (!isset($roleLabels[$roleKey])) abort(404);

        $user  = $request->user();
        $staff = Staff::where("user_id", $user->id)->first();

        if (!$staff || (!in_array($roleKey, $staff->allRoles(), true) && !$user->hasRole("admin"))) {
            abort(403, "You do not have this role.");
        }

        // If this role maps to a real department, go there
        $dept = Department::where("code", $roleKey)->first();
        if ($dept) {
            return redirect()->route("departments.show", $dept->code);
        }

        $title = $roleLabels[$roleKey];
        $stats = DepartmentStatsService::forCode($roleKey);

        return view("hub.role", compact("staff", "title", "roleKey", "stats"));
    }

    /* ============ HELPERS ============ */

    private function statsForDepartment(Department $dept): array
    {
        return DepartmentStatsService::forCode($dept->code);
    }

    private function departmentUrl(Department $dept): string
    {
        $controller = $this->controllerForCode($dept->code);
        if ($controller && class_exists($controller)) {
            return route("dept.{$dept->code}.index");
        }
        return route("departments.show", $dept->code);
    }

    private function roleUrl(string $roleKey, ?Department $dept): string
    {
        if ($dept) {
            $controller = $this->controllerForCode($dept->code);
            if ($controller && class_exists($controller)) {
                return route("dept.{$dept->code}.index");
            }
        }
        return route("roles.hub", $roleKey);
    }

    private function controllerForCode(string $code): ?string
    {
        $map = [
            "health"         => \App\Http\Controllers\Department\HealthController::class,
            "transport"      => \App\Http\Controllers\Department\TransportController::class,
            "hr"             => \App\Http\Controllers\Department\HrController::class,
            "music"          => \App\Http\Controllers\Department\MusicController::class,
            "drama"          => \App\Http\Controllers\Department\DramaController::class,
            "agriculture"    => \App\Http\Controllers\Department\AgricultureController::class,
            "chaplaincy"     => \App\Http\Controllers\Department\ChaplaincyController::class,
            "alumni"         => \App\Http\Controllers\Department\AlumniController::class,
            "special_needs"  => \App\Http\Controllers\Department\SpecialNeedsController::class,
            "counselling"    => \App\Http\Controllers\Department\PsychosocialController::class,
            "procurement"    => \App\Http\Controllers\Department\ProcurementController::class,
            "records"        => \App\Http\Controllers\Department\RecordsController::class,
            "uniform"        => \App\Http\Controllers\Department\UniformController::class,
            "laundry"        => \App\Http\Controllers\Department\LaundryController::class,
            "stores"         => \App\Http\Controllers\Department\StoresController::class,
            "maintenance"    => \App\Http\Controllers\Department\MaintenanceController::class,
            "front_office"   => \App\Http\Controllers\Department\FrontOfficeController::class,
            "ict"            => \App\Http\Controllers\Department\IctController::class,
            "discipline"     => \App\Http\Controllers\Department\DisciplineController::class,
            "sports"         => \App\Http\Controllers\Department\SportsController::class,
            "duty"           => \App\Http\Controllers\Department\DutyController::class,
            "boarding"       => \App\Http\Controllers\Department\BoardingController::class,
            "feeding"        => \App\Http\Controllers\Department\FeedingController::class,
            "library"        => \App\Http\Controllers\Department\LibraryController::class,
            "guidance"       => \App\Http\Controllers\Department\GuidanceController::class,
            "environment"    => \App\Http\Controllers\Department\EnvironmentController::class,
            "security"       => \App\Http\Controllers\Department\SecurityController::class,
            "finance"        => \App\Http\Controllers\Department\FinanceController::class,
            "administration" => \App\Http\Controllers\Department\AdministrationController::class,
        ];
        return $map[$code] ?? null;
    }
}