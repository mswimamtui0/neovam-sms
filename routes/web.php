<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function () { return view("welcome"); });

Route::get("/dashboard", function () {
    $user = auth()->user();
    if (!$user) return redirect()->route("login");

    // Super admin
    if ($user->hasRole("super_admin")) {
        return redirect()->route("super-admin.dashboard");
    }

    // Staff → auto-route by department
    $staff = \App\Models\Staff::where("user_id", $user->id)->first();
    if ($staff && $staff->hasAnyDepartmentRole()) {
        $route = \App\Services\Department\DepartmentRouter::landingRoute($staff);
        return redirect()->route($route);
    }

    // Admin roles (no staff record)
    if ($user->hasRole("admin") || $user->hasRole("head_of_school")) {
        return redirect()->route("admin.dashboard");
    }
    if ($user->hasRole("academic_master")) {
        return redirect()->route("academic.dashboard");
    }
    if ($user->hasRole("parent"))  return redirect()->route("parent.dashboard");
    if ($user->hasRole("student")) return redirect()->route("student.dashboard");

    return view("dashboard");
})->middleware("auth")->name("dashboard");

require __DIR__ . "/admin.php";
require __DIR__ . "/academic.php";
require __DIR__ . "/teacher.php";
require __DIR__ . "/class-teacher.php";
require __DIR__ . "/shared.php";
require __DIR__ . "/parent.php";
require __DIR__ . "/student.php";
require __DIR__ . "/auth.php";
// SMS Delivery Webhook (called by SMS gateway, no auth)
Route::post("/sms/delivery-webhook", [\App\Http\Controllers\Admin\SmsDeliveryController::class, "webhook"])
 ->name("sms.delivery-webhook");
Route::get("/offline", fn() => view("offline"))->name("offline");
// ============ DEPARTMENT HUB ROUTES ============
Route::middleware(["auth"])->group(function () {
    Route::get("/hub", [\App\Http\Controllers\DepartmentHubController::class, "index"])->name("hub.index");
    Route::get("/departments/{code}", [\App\Http\Controllers\DepartmentHubController::class, "show"])->name("departments.show");
    Route::get("/roles/{roleKey}", [\App\Http\Controllers\DepartmentHubController::class, "role"])->name("roles.hub");
    Route::get("/staff/workspace", [\App\Http\Controllers\StaffWorkspaceController::class, "index"])->name("staff.workspace");
});
// ============ SHARED DEPARTMENT ROUTES (all members see the same data) ============
Route::middleware(["auth"])->prefix("dept")->group(function () {

    // Health
    Route::middleware("dept:health")->prefix("health")->name("dept.health.")->group(function () {
        Route::get("/",         [\App\Http\Controllers\Department\HealthController::class, "index"])->name("index");
        Route::get("/create",   [\App\Http\Controllers\Department\HealthController::class, "create"])->name("create");
        Route::post("/",        [\App\Http\Controllers\Department\HealthController::class, "store"])->name("store");
        Route::get("/{id}",     [\App\Http\Controllers\Department\HealthController::class, "show"])->name("show");
        Route::get("/{id}/edit",[\App\Http\Controllers\Department\HealthController::class, "edit"])->name("edit");
        Route::put("/{id}",     [\App\Http\Controllers\Department\HealthController::class, "update"])->name("update");
        Route::delete("/{id}",  [\App\Http\Controllers\Department\HealthController::class, "destroy"])->name("destroy");
    });

    // Discipline
    Route::middleware("dept:discipline")->prefix("discipline")->name("dept.discipline.")->group(function () {
        Route::get("/",         [\App\Http\Controllers\Department\DisciplineController::class, "index"])->name("index");
        Route::get("/create",   [\App\Http\Controllers\Department\DisciplineController::class, "create"])->name("create");
        Route::post("/",        [\App\Http\Controllers\Department\DisciplineController::class, "store"])->name("store");
        Route::get("/{id}",     [\App\Http\Controllers\Department\DisciplineController::class, "show"])->name("show");
        Route::get("/{id}/edit",[\App\Http\Controllers\Department\DisciplineController::class, "edit"])->name("edit");
        Route::put("/{id}",     [\App\Http\Controllers\Department\DisciplineController::class, "update"])->name("update");
        Route::delete("/{id}",  [\App\Http\Controllers\Department\DisciplineController::class, "destroy"])->name("destroy");
    });

    // Sports
    Route::middleware("dept:sports")->prefix("sports")->name("dept.sports.")->group(function () {
        Route::get("/",         [\App\Http\Controllers\Department\SportsController::class, "index"])->name("index");
        Route::get("/create",   [\App\Http\Controllers\Department\SportsController::class, "create"])->name("create");
        Route::post("/",        [\App\Http\Controllers\Department\SportsController::class, "store"])->name("store");
        Route::get("/{id}",     [\App\Http\Controllers\Department\SportsController::class, "show"])->name("show");
        Route::get("/{id}/edit",[\App\Http\Controllers\Department\SportsController::class, "edit"])->name("edit");
        Route::put("/{id}",     [\App\Http\Controllers\Department\SportsController::class, "update"])->name("update");
        Route::delete("/{id}",  [\App\Http\Controllers\Department\SportsController::class, "destroy"])->name("destroy");
    });

    // Duty
    Route::middleware("dept:duty")->prefix("duty")->name("dept.duty.")->group(function () {
        Route::get("/",         [\App\Http\Controllers\Department\DutyController::class, "index"])->name("index");
        Route::get("/create",   [\App\Http\Controllers\Department\DutyController::class, "create"])->name("create");
        Route::post("/",        [\App\Http\Controllers\Department\DutyController::class, "store"])->name("store");
        Route::get("/{id}",     [\App\Http\Controllers\Department\DutyController::class, "show"])->name("show");
        Route::get("/{id}/edit",[\App\Http\Controllers\Department\DutyController::class, "edit"])->name("edit");
        Route::put("/{id}",     [\App\Http\Controllers\Department\DutyController::class, "update"])->name("update");
        Route::delete("/{id}",  [\App\Http\Controllers\Department\DutyController::class, "destroy"])->name("destroy");
    });

    // Boarding
    Route::middleware("dept:boarding")->prefix("boarding")->name("dept.boarding.")->group(function () {
        Route::get("/",         [\App\Http\Controllers\Department\BoardingController::class, "index"])->name("index");
        Route::get("/create",   [\App\Http\Controllers\Department\BoardingController::class, "create"])->name("create");
        Route::post("/",        [\App\Http\Controllers\Department\BoardingController::class, "store"])->name("store");
        Route::get("/{id}",     [\App\Http\Controllers\Department\BoardingController::class, "show"])->name("show");
        Route::get("/{id}/edit",[\App\Http\Controllers\Department\BoardingController::class, "edit"])->name("edit");
        Route::put("/{id}",     [\App\Http\Controllers\Department\BoardingController::class, "update"])->name("update");
        Route::delete("/{id}",  [\App\Http\Controllers\Department\BoardingController::class, "destroy"])->name("destroy");
    });

    // Feeding
    Route::middleware("dept:feeding")->prefix("feeding")->name("dept.feeding.")->group(function () {
        Route::get("/",         [\App\Http\Controllers\Department\FeedingController::class, "index"])->name("index");
        Route::get("/create",   [\App\Http\Controllers\Department\FeedingController::class, "create"])->name("create");
        Route::post("/",        [\App\Http\Controllers\Department\FeedingController::class, "store"])->name("store");
        Route::get("/{id}",     [\App\Http\Controllers\Department\FeedingController::class, "show"])->name("show");
        Route::get("/{id}/edit",[\App\Http\Controllers\Department\FeedingController::class, "edit"])->name("edit");
        Route::put("/{id}",     [\App\Http\Controllers\Department\FeedingController::class, "update"])->name("update");
        Route::delete("/{id}",  [\App\Http\Controllers\Department\FeedingController::class, "destroy"])->name("destroy");
    });

    // Library
    Route::middleware("dept:library")->prefix("library")->name("dept.library.")->group(function () {
        Route::get("/",         [\App\Http\Controllers\Department\LibraryController::class, "index"])->name("index");
        Route::get("/create",   [\App\Http\Controllers\Department\LibraryController::class, "create"])->name("create");
        Route::post("/",        [\App\Http\Controllers\Department\LibraryController::class, "store"])->name("store");
        Route::get("/{id}",     [\App\Http\Controllers\Department\LibraryController::class, "show"])->name("show");
        Route::get("/{id}/edit",[\App\Http\Controllers\Department\LibraryController::class, "edit"])->name("edit");
        Route::put("/{id}",     [\App\Http\Controllers\Department\LibraryController::class, "update"])->name("update");
        Route::delete("/{id}",  [\App\Http\Controllers\Department\LibraryController::class, "destroy"])->name("destroy");
    });

    // Guidance
    Route::middleware("dept:guidance")->prefix("guidance")->name("dept.guidance.")->group(function () {
        Route::get("/",         [\App\Http\Controllers\Department\GuidanceController::class, "index"])->name("index");
        Route::get("/create",   [\App\Http\Controllers\Department\GuidanceController::class, "create"])->name("create");
        Route::post("/",        [\App\Http\Controllers\Department\GuidanceController::class, "store"])->name("store");
        Route::get("/{id}",     [\App\Http\Controllers\Department\GuidanceController::class, "show"])->name("show");
        Route::get("/{id}/edit",[\App\Http\Controllers\Department\GuidanceController::class, "edit"])->name("edit");
        Route::put("/{id}",     [\App\Http\Controllers\Department\GuidanceController::class, "update"])->name("update");
        Route::delete("/{id}",  [\App\Http\Controllers\Department\GuidanceController::class, "destroy"])->name("destroy");
    });

    // Environment
    Route::middleware("dept:environment")->prefix("environment")->name("dept.environment.")->group(function () {
        Route::get("/",         [\App\Http\Controllers\Department\EnvironmentController::class, "index"])->name("index");
        Route::get("/create",   [\App\Http\Controllers\Department\EnvironmentController::class, "create"])->name("create");
        Route::post("/",        [\App\Http\Controllers\Department\EnvironmentController::class, "store"])->name("store");
        Route::get("/{id}",     [\App\Http\Controllers\Department\EnvironmentController::class, "show"])->name("show");
        Route::get("/{id}/edit",[\App\Http\Controllers\Department\EnvironmentController::class, "edit"])->name("edit");
        Route::put("/{id}",     [\App\Http\Controllers\Department\EnvironmentController::class, "update"])->name("update");
        Route::delete("/{id}",  [\App\Http\Controllers\Department\EnvironmentController::class, "destroy"])->name("destroy");
    });

    // Security
    Route::middleware("dept:security")->prefix("security")->name("dept.security.")->group(function () {
        Route::get("/",         [\App\Http\Controllers\Department\SecurityController::class, "index"])->name("index");
        Route::get("/create",   [\App\Http\Controllers\Department\SecurityController::class, "create"])->name("create");
        Route::post("/",        [\App\Http\Controllers\Department\SecurityController::class, "store"])->name("store");
        Route::get("/{id}",     [\App\Http\Controllers\Department\SecurityController::class, "show"])->name("show");
        Route::get("/{id}/edit",[\App\Http\Controllers\Department\SecurityController::class, "edit"])->name("edit");
        Route::put("/{id}",     [\App\Http\Controllers\Department\SecurityController::class, "update"])->name("update");
        Route::delete("/{id}",  [\App\Http\Controllers\Department\SecurityController::class, "destroy"])->name("destroy");
    });

    // Finance
    Route::middleware("dept:finance")->prefix("finance")->name("dept.finance.")->group(function () {
        Route::get("/",         [\App\Http\Controllers\Department\FinanceController::class, "index"])->name("index");
        Route::get("/create",   [\App\Http\Controllers\Department\FinanceController::class, "create"])->name("create");
        Route::post("/",        [\App\Http\Controllers\Department\FinanceController::class, "store"])->name("store");
        Route::get("/{id}",     [\App\Http\Controllers\Department\FinanceController::class, "show"])->name("show");
        Route::get("/{id}/edit",[\App\Http\Controllers\Department\FinanceController::class, "edit"])->name("edit");
        Route::put("/{id}",     [\App\Http\Controllers\Department\FinanceController::class, "update"])->name("update");
        Route::delete("/{id}",  [\App\Http\Controllers\Department\FinanceController::class, "destroy"])->name("destroy");
    });

    // Administration
    Route::middleware("dept:administration")->prefix("administration")->name("dept.administration.")->group(function () {
        Route::get("/",         [\App\Http\Controllers\Department\AdministrationController::class, "index"])->name("index");
        Route::get("/create",   [\App\Http\Controllers\Department\AdministrationController::class, "create"])->name("create");
        Route::post("/",        [\App\Http\Controllers\Department\AdministrationController::class, "store"])->name("store");
        Route::get("/{id}",     [\App\Http\Controllers\Department\AdministrationController::class, "show"])->name("show");
        Route::get("/{id}/edit",[\App\Http\Controllers\Department\AdministrationController::class, "edit"])->name("edit");
        Route::put("/{id}",     [\App\Http\Controllers\Department\AdministrationController::class, "update"])->name("update");
        Route::delete("/{id}",  [\App\Http\Controllers\Department\AdministrationController::class, "destroy"])->name("destroy");
    });

// ============ COMBINED HUBS ============
Route::middleware(["auth"])->prefix("hubs")->name("hubs.")->group(function () {
    Route::get("/boarding",  [\App\Http\Controllers\CombinedHubController::class, "boarding"])->name("boarding");
    Route::get("/sports",    [\App\Http\Controllers\CombinedHubController::class, "sports"])->name("sports");
    Route::get("/transport", [\App\Http\Controllers\CombinedHubController::class, "transport"])->name("transport");
});

    // Transport
    Route::middleware("dept:transport")->prefix("transport")->name("dept.transport.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\TransportController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\TransportController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\TransportController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\TransportController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\TransportController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\TransportController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\TransportController::class, "destroy"])->name("destroy");
    });

    // Stores
    Route::middleware("dept:stores")->prefix("stores")->name("dept.stores.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\StoresController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\StoresController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\StoresController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\StoresController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\StoresController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\StoresController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\StoresController::class, "destroy"])->name("destroy");
    });

    // Maintenance
    Route::middleware("dept:maintenance")->prefix("maintenance")->name("dept.maintenance.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\MaintenanceController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\MaintenanceController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\MaintenanceController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\MaintenanceController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\MaintenanceController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\MaintenanceController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\MaintenanceController::class, "destroy"])->name("destroy");
    });

    // Front Office
    Route::middleware("dept:front_office")->prefix("front-office")->name("dept.front_office.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\FrontOfficeController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\FrontOfficeController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\FrontOfficeController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\FrontOfficeController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\FrontOfficeController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\FrontOfficeController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\FrontOfficeController::class, "destroy"])->name("destroy");
    });

    // ICT
    Route::middleware("dept:ict")->prefix("ict")->name("dept.ict.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\IctController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\IctController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\IctController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\IctController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\IctController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\IctController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\IctController::class, "destroy"])->name("destroy");
    });

    // Human Resources
    Route::middleware("dept:hr")->prefix("hr")->name("dept.hr.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\HrController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\HrController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\HrController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\HrController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\HrController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\HrController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\HrController::class, "destroy"])->name("destroy");
    });

    // Procurement
    Route::middleware("dept:procurement")->prefix("procurement")->name("dept.procurement.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\ProcurementController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\ProcurementController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\ProcurementController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\ProcurementController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\ProcurementController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\ProcurementController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\ProcurementController::class, "destroy"])->name("destroy");
    });

    // Records
    Route::middleware("dept:records")->prefix("records")->name("dept.records.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\RecordsController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\RecordsController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\RecordsController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\RecordsController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\RecordsController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\RecordsController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\RecordsController::class, "destroy"])->name("destroy");
    });

    // Uniform
    Route::middleware("dept:uniform")->prefix("uniform")->name("dept.uniform.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\UniformController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\UniformController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\UniformController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\UniformController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\UniformController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\UniformController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\UniformController::class, "destroy"])->name("destroy");
    });

    // Laundry
    Route::middleware("dept:laundry")->prefix("laundry")->name("dept.laundry.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\LaundryController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\LaundryController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\LaundryController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\LaundryController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\LaundryController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\LaundryController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\LaundryController::class, "destroy"])->name("destroy");
    });

    // Music
    Route::middleware("dept:music")->prefix("music")->name("dept.music.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\MusicController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\MusicController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\MusicController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\MusicController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\MusicController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\MusicController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\MusicController::class, "destroy"])->name("destroy");
    });

    // Drama
    Route::middleware("dept:drama")->prefix("drama")->name("dept.drama.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\DramaController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\DramaController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\DramaController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\DramaController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\DramaController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\DramaController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\DramaController::class, "destroy"])->name("destroy");
    });

    // Agriculture
    Route::middleware("dept:agriculture")->prefix("agriculture")->name("dept.agriculture.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\AgricultureController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\AgricultureController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\AgricultureController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\AgricultureController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\AgricultureController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\AgricultureController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\AgricultureController::class, "destroy"])->name("destroy");
    });

    // Chaplaincy
    Route::middleware("dept:chaplaincy")->prefix("chaplaincy")->name("dept.chaplaincy.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\ChaplaincyController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\ChaplaincyController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\ChaplaincyController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\ChaplaincyController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\ChaplaincyController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\ChaplaincyController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\ChaplaincyController::class, "destroy"])->name("destroy");
    });

    // Alumni
    Route::middleware("dept:alumni")->prefix("alumni")->name("dept.alumni.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\AlumniController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\AlumniController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\AlumniController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\AlumniController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\AlumniController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\AlumniController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\AlumniController::class, "destroy"])->name("destroy");
    });

    // Special Needs
    Route::middleware("dept:special_needs")->prefix("special-needs")->name("dept.special_needs.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\SpecialNeedsController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\SpecialNeedsController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\SpecialNeedsController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\SpecialNeedsController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\SpecialNeedsController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\SpecialNeedsController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\SpecialNeedsController::class, "destroy"])->name("destroy");
    });

    // Counselling / Psychosocial
    Route::middleware("dept:counselling")->prefix("counselling")->name("dept.counselling.")->group(function () {
        Route::get("/",          [\App\Http\Controllers\Department\PsychosocialController::class, "index"])->name("index");
        Route::get("/create",    [\App\Http\Controllers\Department\PsychosocialController::class, "create"])->name("create");
        Route::post("/",         [\App\Http\Controllers\Department\PsychosocialController::class, "store"])->name("store");
        Route::get("/{id}",      [\App\Http\Controllers\Department\PsychosocialController::class, "show"])->name("show");
        Route::get("/{id}/edit", [\App\Http\Controllers\Department\PsychosocialController::class, "edit"])->name("edit");
        Route::put("/{id}",      [\App\Http\Controllers\Department\PsychosocialController::class, "update"])->name("update");
        Route::delete("/{id}",   [\App\Http\Controllers\Department\PsychosocialController::class, "destroy"])->name("destroy");
    });

// ============ EMERGENCY ALERTS ============
Route::middleware(["auth"])->prefix("emergencies")->name("emergencies.")->group(function () {
    Route::get("/",              [\App\Http\Controllers\EmergencyController::class, "index"])->name("index");
    Route::get("/create",        [\App\Http\Controllers\EmergencyController::class, "create"])->name("create");
    Route::post("/",             [\App\Http\Controllers\EmergencyController::class, "store"])->name("store");
    Route::get("/{id}",          [\App\Http\Controllers\EmergencyController::class, "show"])->name("show");
    Route::get("/{id}/edit",     [\App\Http\Controllers\EmergencyController::class, "edit"])->name("edit");
    Route::put("/{id}",          [\App\Http\Controllers\EmergencyController::class, "update"])->name("update");
    Route::delete("/{id}",       [\App\Http\Controllers\EmergencyController::class, "destroy"])->name("destroy");
    Route::post("/{id}/resend",  [\App\Http\Controllers\EmergencyController::class, "resend"])->name("resend");
});
});