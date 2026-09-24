<?php

use App\Http\Controllers\Academic\AcademicController;
use App\Http\Controllers\Academic\TimetableController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth", "role:academic_master,head_of_school,admin"])
    ->prefix("academic")
    ->name("academic.")
    ->group(function () {

    Route::get("/",                     [AcademicController::class, "dashboard"])->name("dashboard");

    // Syllabus
    Route::get("/syllabus",             [AcademicController::class, "syllabus"])->name("syllabus");
    Route::get("/syllabus/create",      [AcademicController::class, "createSyllabus"])->name("syllabus.create");
    Route::post("/syllabus",            [AcademicController::class, "storeSyllabus"])->name("syllabus.store");

    // Timetable
    Route::get("/timetable",            [TimetableController::class, "index"])->name("timetable");
    Route::get("/timetable/grid",       [TimetableController::class, "grid"])->name("timetable.grid");
    Route::get("/timetable/create",     [TimetableController::class, "create"])->name("timetable.create");
    Route::post("/timetable",           [TimetableController::class, "store"])->name("timetable.store");
    Route::get("/timetable/{timetable}/edit",  [TimetableController::class, "edit"])->name("timetable.edit");
    Route::put("/timetable/{timetable}",       [TimetableController::class, "update"])->name("timetable.update");
    Route::delete("/timetable/{timetable}",    [TimetableController::class, "destroy"])->name("timetable.destroy");

    // Exams
    Route::get("/exams",                [AcademicController::class, "exams"])->name("exams");
    Route::get("/exams/create",         [AcademicController::class, "createExam"])->name("exams.create");
    Route::post("/exams",               [AcademicController::class, "storeExam"])->name("exams.store");
    Route::get("/exams/{exam}/timetable",  [AcademicController::class, "examTimetable"])->name("exams.timetable");
    Route::post("/exams/{exam}/timetable", [AcademicController::class, "storeExamTimetable"])->name("exams.timetable.store");

    Route::get("/results",              [AcademicController::class, "results"])->name("results");
    Route::get("/teachers",             [AcademicController::class, "teachers"])->name("teachers");
    Route::get("/performance",          [AcademicController::class, "studentPerformance"])->name("performance");

    Route::get("/calendar",             [AcademicController::class, "calendar"])->name("calendar");
    Route::get("/calendar/create",      [AcademicController::class, "createCalendar"])->name("calendar.create");
    Route::post("/calendar",            [AcademicController::class, "storeCalendar"])->name("calendar.store");

    Route::get("/meetings",             [AcademicController::class, "meetings"])->name("meetings");
    Route::get("/meetings/create",      [AcademicController::class, "createMeeting"])->name("meetings.create");
    Route::post("/meetings",            [AcademicController::class, "storeMeeting"])->name("meetings.store");

    Route::get("/reports",              [AcademicController::class, "reports"])->name("reports");
    Route::get("/national-exams",       [AcademicController::class, "nationalExams"])->name("national");
});