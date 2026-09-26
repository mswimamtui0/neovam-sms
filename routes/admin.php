<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\CommunicationController;
use App\Http\Controllers\Admin\EmergencyController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\SchoolSettingsController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth", "role:admin,head_of_school"])
 ->prefix("admin")
 ->name("admin.")
 ->group(function () {

 Route::get("/", fn() => view("admin.dashboard"))->name("dashboard");

 Route::prefix("api")->name("api.")->group(function () {
 Route::get("/classrooms-by-level", [StudentController::class, "classroomsByLevel"])->name("classrooms.byLevel");
 Route::get("/classes-by-level", [EmergencyController::class, "classesByLevel"])->name("classes.byLevel");
 Route::get("/streams-by-class", [EmergencyController::class, "streamsByClass"])->name("streams.byClass");
 Route::get("/students-by-class", [EmergencyController::class, "studentsByClass"])->name("students.byClass");
 Route::get("/search-students", [EmergencyController::class, "searchStudents"])->name("students.search");

 // Communication
 Route::get("/comm-classrooms-by-level", [CommunicationController::class, "classroomsByLevel"])->name("comm.classrooms.byLevel");
 Route::get("/comm-streams-by-class", [CommunicationController::class, "streamsByClassroom"])->name("comm.streams.byClass");
 Route::post("/preview-recipients", [CommunicationController::class, "previewRecipients"])->name("preview.recipients");
 });

 Route::middleware(["role:admin"])->group(function () {
 Route::get("/settings/school", [SchoolSettingsController::class, "edit"])->name("settings.school");
 Route::get("/settings/sms", [\App\Http\Controllers\Admin\SmsSettingsController::class, "index"])->name("settings.sms");
 Route::post("/settings/sms", [\App\Http\Controllers\Admin\SmsSettingsController::class, "update"])->name("settings.sms.update");
 Route::post("/settings/sms/test", [\App\Http\Controllers\Admin\SmsSettingsController::class, "test"])->name("settings.sms.test");
 Route::get("/settings/pwa", fn() => view("admin.settings.pwa"))->name("settings.pwa");

 // SMS Templates
 Route::get("/sms-templates", [\App\Http\Controllers\Admin\SmsTemplateController::class, "index"])->name("sms-templates.index");
 Route::get("/sms-templates/create", [\App\Http\Controllers\Admin\SmsTemplateController::class, "create"])->name("sms-templates.create");
 Route::post("/sms-templates", [\App\Http\Controllers\Admin\SmsTemplateController::class, "store"])->name("sms-templates.store");
 Route::get("/sms-templates/{smsTemplate}/edit", [\App\Http\Controllers\Admin\SmsTemplateController::class, "edit"])->name("sms-templates.edit");
 Route::put("/sms-templates/{smsTemplate}", [\App\Http\Controllers\Admin\SmsTemplateController::class, "update"])->name("sms-templates.update");
 Route::delete("/sms-templates/{smsTemplate}", [\App\Http\Controllers\Admin\SmsTemplateController::class, "destroy"])->name("sms-templates.destroy");

 // SMS Triggers
 Route::get("/sms-triggers", [\App\Http\Controllers\Admin\SmsTriggerController::class, "index"])->name("sms-triggers.index");
 Route::put("/sms-triggers/{trigger}", [\App\Http\Controllers\Admin\SmsTriggerController::class, "update"])->name("sms-triggers.update");
 Route::post("/sms-triggers/bulk-toggle", [\App\Http\Controllers\Admin\SmsTriggerController::class, "bulkToggle"])->name("sms-triggers.bulk-toggle");

 // SMS Control Center
 Route::get("/sms-control", [\App\Http\Controllers\Admin\SmsControlController::class, "index"])->name("sms-control.index");
 Route::post("/sms-control", [\App\Http\Controllers\Admin\SmsControlController::class, "update"])->name("sms-control.update");
 Route::post("/sms-control/preset", [\App\Http\Controllers\Admin\SmsControlController::class, "preset"])->name("sms-control.preset");
 Route::get("/sms-control/skipped", [\App\Http\Controllers\Admin\SmsControlController::class, "skipped"])->name("sms-control.skipped");
 Route::delete("/sms-control/skipped",[\App\Http\Controllers\Admin\SmsControlController::class, "clearSkipped"])->name("sms-control.clear-skipped");

 // ============ UNIFIED SMS SETTINGS ============
 Route::prefix("sms-settings")->name("sms-settings.")->group(function () {
 Route::get("/", [\App\Http\Controllers\Admin\SmsSettingsHubController::class, "index"])->name("index");
 Route::post("/master-toggle", [\App\Http\Controllers\Admin\SmsSettingsHubController::class, "masterToggle"])->name("master-toggle");
 Route::post("/test-mode", [\App\Http\Controllers\Admin\SmsSettingsHubController::class, "testMode"])->name("test-mode");
 Route::post("/update-control", [\App\Http\Controllers\Admin\SmsSettingsHubController::class, "updateControl"])->name("update-control");
 Route::post("/preset", [\App\Http\Controllers\Admin\SmsSettingsHubController::class, "preset"])->name("preset");
 Route::post("/gateway", [\App\Http\Controllers\Admin\SmsSettingsHubController::class, "gatewayUpdate"])->name("gateway");
 Route::post("/send-test", [\App\Http\Controllers\Admin\SmsSettingsHubController::class, "sendTest"])->name("send-test");
 Route::put("/template/{template}", [\App\Http\Controllers\Admin\SmsSettingsHubController::class, "templateUpdate"])->name("template.update");
 Route::put("/trigger/{trigger}", [\App\Http\Controllers\Admin\SmsSettingsHubController::class, "triggerToggle"])->name("trigger.toggle");
 Route::post("/trigger-bulk", [\App\Http\Controllers\Admin\SmsSettingsHubController::class, "triggerBulk"])->name("trigger.bulk");
 Route::post("/topup", [\App\Http\Controllers\Admin\SmsSettingsHubController::class, "topup"])->name("topup");
 Route::delete("/clear-skipped", [\App\Http\Controllers\Admin\SmsSettingsHubController::class, "clearSkipped"])->name("clear-skipped");
 });

 // SMS Delivery Reports
 Route::get("/sms-delivery", [\App\Http\Controllers\Admin\SmsDeliveryController::class, "index"])->name("sms-delivery.index");
 Route::put("/sms-delivery/{log}", [\App\Http\Controllers\Admin\SmsDeliveryController::class, "update"])->name("sms-delivery.update");
 Route::post("/sms-delivery/retry", [\App\Http\Controllers\Admin\SmsDeliveryController::class, "retry"])->name("sms-delivery.retry");

 // SMS Cost Tracking
 Route::get("/sms-cost", [\App\Http\Controllers\Admin\SmsCostController::class, "index"])->name("sms-cost.index");
 Route::get("/sms-cost/pricing", [\App\Http\Controllers\Admin\SmsCostController::class, "pricing"])->name("sms-cost.pricing");
 Route::post("/sms-cost/pricing", [\App\Http\Controllers\Admin\SmsCostController::class, "storePricing"])->name("sms-cost.pricing.store");
 Route::delete("/sms-cost/pricing/{pricingRule}", [\App\Http\Controllers\Admin\SmsCostController::class, "destroyPricing"])->name("sms-cost.pricing.destroy");
 Route::post("/sms-cost/topup", [\App\Http\Controllers\Admin\SmsCostController::class, "topup"])->name("sms-cost.topup");
 Route::get("/sms-cost/transactions", [\App\Http\Controllers\Admin\SmsCostController::class, "transactions"])->name("sms-cost.transactions");

 // Staff Attendance
 Route::get("/staff-attendance", [\App\Http\Controllers\Admin\StaffAttendanceController::class, "index"])->name("staff-attendance.index");
 Route::get("/staff-attendance/missing", [\App\Http\Controllers\Admin\StaffAttendanceController::class, "missing"])->name("staff-attendance.missing");
 Route::get("/staff-attendance/report", [\App\Http\Controllers\Admin\StaffAttendanceController::class, "report"])->name("staff-attendance.report");
 Route::post("/staff-attendance", [\App\Http\Controllers\Admin\StaffAttendanceController::class, "store"])->name("staff-attendance.store");
 Route::get("/staff-attendance/{attendance}/edit", [\App\Http\Controllers\Admin\StaffAttendanceController::class, "edit"])->name("staff-attendance.edit");
 Route::put("/staff-attendance/{attendance}", [\App\Http\Controllers\Admin\StaffAttendanceController::class, "update"])->name("staff-attendance.update");

 // Staff Transfers
 Route::get("/staff-transfers", [\App\Http\Controllers\Admin\StaffTransferController::class, "index"])->name("staff-transfers.index");
 Route::get("/staff-transfers/create", [\App\Http\Controllers\Admin\StaffTransferController::class, "create"])->name("staff-transfers.create");
 Route::post("/staff-transfers", [\App\Http\Controllers\Admin\StaffTransferController::class, "store"])->name("staff-transfers.store");
 Route::get("/staff-transfers/{transfer}", [\App\Http\Controllers\Admin\StaffTransferController::class, "show"])->name("staff-transfers.show");
 Route::get("/staff-transfers/{transfer}/edit", [\App\Http\Controllers\Admin\StaffTransferController::class, "edit"])->name("staff-transfers.edit");
 Route::put("/staff-transfers/{transfer}", [\App\Http\Controllers\Admin\StaffTransferController::class, "update"])->name("staff-transfers.update");
 Route::delete("/staff-transfers/{transfer}", [\App\Http\Controllers\Admin\StaffTransferController::class, "destroy"])->name("staff-transfers.destroy");
 Route::post("/staff-transfers/{transfer}/approve",[\App\Http\Controllers\Admin\StaffTransferController::class, "approve"])->name("staff-transfers.approve");
 Route::post("/staff-transfers/{transfer}/reject", [\App\Http\Controllers\Admin\StaffTransferController::class, "reject"])->name("staff-transfers.reject");

 // Performance Reviews (Head of School)
 Route::get("/performance-reviews", [\App\Http\Controllers\Admin\PerformanceReviewController::class, "index"])->name("performance-reviews.index");
 Route::get("/performance-reviews/dashboard", [\App\Http\Controllers\Admin\PerformanceReviewController::class, "dashboard"])->name("performance-reviews.dashboard");
 Route::get("/performance-reviews/{report}", [\App\Http\Controllers\Admin\PerformanceReviewController::class, "show"])->name("performance-reviews.show");
 Route::post("/performance-reviews/{report}/approve", [\App\Http\Controllers\Admin\PerformanceReviewController::class, "approve"])->name("performance-reviews.approve");
 Route::post("/performance-reviews/{report}/reject", [\App\Http\Controllers\Admin\PerformanceReviewController::class, "reject"])->name("performance-reviews.reject");
 Route::post("/performance-reviews/{report}/revision", [\App\Http\Controllers\Admin\PerformanceReviewController::class, "requestRevision"])->name("performance-reviews.revision");

 // Department Activities
 Route::get("/department-activities", [\App\Http\Controllers\Admin\DepartmentActivityController::class, "index"])->name("department-activities.index");
 Route::get("/department-activities/dashboard", [\App\Http\Controllers\Admin\DepartmentActivityController::class, "dashboard"])->name("department-activities.dashboard");
 Route::get("/department-activities/create", [\App\Http\Controllers\Admin\DepartmentActivityController::class, "create"])->name("department-activities.create");
 Route::post("/department-activities", [\App\Http\Controllers\Admin\DepartmentActivityController::class, "store"])->name("department-activities.store");
 Route::get("/department-activities/{activity}", [\App\Http\Controllers\Admin\DepartmentActivityController::class, "show"])->name("department-activities.show");
 Route::get("/department-activities/{activity}/edit", [\App\Http\Controllers\Admin\DepartmentActivityController::class, "edit"])->name("department-activities.edit");
 Route::put("/department-activities/{activity}", [\App\Http\Controllers\Admin\DepartmentActivityController::class, "update"])->name("department-activities.update");
 Route::delete("/department-activities/{activity}", [\App\Http\Controllers\Admin\DepartmentActivityController::class, "destroy"])->name("department-activities.destroy");
 Route::post("/department-activities/{activity}/approve",[\App\Http\Controllers\Admin\DepartmentActivityController::class, "approve"])->name("department-activities.approve");

 // Income Tracking
 Route::get("/income-tracking", [\App\Http\Controllers\Admin\IncomeTrackingController::class, "index"])->name("income-tracking.index");
 Route::get("/income-tracking/students", [\App\Http\Controllers\Admin\IncomeTrackingController::class, "students"])->name("income-tracking.students");

 // Analytics
 Route::get("/analytics", [\App\Http\Controllers\Admin\AnalyticsController::class, "index"])->name("analytics.index");
 Route::get("/analytics/academic", [\App\Http\Controllers\Admin\AnalyticsController::class, "academic"])->name("analytics.academic");
 Route::get("/analytics/attendance", [\App\Http\Controllers\Admin\AnalyticsController::class, "attendance"])->name("analytics.attendance");
 Route::get("/analytics/financial", [\App\Http\Controllers\Admin\AnalyticsController::class, "financial"])->name("analytics.financial");
 Route::get("/analytics/sms", [\App\Http\Controllers\Admin\AnalyticsController::class, "sms"])->name("analytics.sms");
 Route::get("/analytics/staff", [\App\Http\Controllers\Admin\AnalyticsController::class, "staff"])->name("analytics.staff");

 // Backups
 Route::get("/backups", [\App\Http\Controllers\Admin\BackupController::class, "index"])->name("backups.index");
 Route::post("/backups/full", [\App\Http\Controllers\Admin\BackupController::class, "createFull"])->name("backups.full");
 Route::post("/backups/school", [\App\Http\Controllers\Admin\BackupController::class, "createSchool"])->name("backups.school");
 Route::get("/backups/{backup}/download", [\App\Http\Controllers\Admin\BackupController::class, "download"])->name("backups.download");
 Route::post("/backups/{backup}/restore", [\App\Http\Controllers\Admin\BackupController::class, "restore"])->name("backups.restore");
 Route::delete("/backups/{backup}", [\App\Http\Controllers\Admin\BackupController::class, "destroy"])->name("backups.destroy");

 // Yearly Planner
 Route::prefix("planner")->name("planner.")->group(function () {
 // Duty
 Route::get("/duty", [\App\Http\Controllers\Admin\YearlyPlannerController::class, "dutyIndex"])->name("duty.index");
 Route::get("/duty/create", [\App\Http\Controllers\Admin\YearlyPlannerController::class, "dutyCreate"])->name("duty.create");
 Route::post("/duty", [\App\Http\Controllers\Admin\YearlyPlannerController::class, "dutyStore"])->name("duty.store");
 Route::get("/duty/{calendar}", [\App\Http\Controllers\Admin\YearlyPlannerController::class, "dutyShow"])->name("duty.show");
 Route::post("/duty/{calendar}/assign", [\App\Http\Controllers\Admin\YearlyPlannerController::class, "dutyAssign"])->name("duty.assign");
 Route::post("/duty/{calendar}/bulk", [\App\Http\Controllers\Admin\YearlyPlannerController::class, "dutyBulkAssign"])->name("duty.bulk");
 Route::post("/duty/{calendar}/activate", [\App\Http\Controllers\Admin\YearlyPlannerController::class, "dutyActivate"])->name("duty.activate");
 Route::delete("/duty-item/{assignment}", [\App\Http\Controllers\Admin\YearlyPlannerController::class, "dutyDestroy"])->name("duty.destroy");

 // Class teacher roster
 Route::get("/class-teachers", [\App\Http\Controllers\Admin\YearlyPlannerController::class, "classTeacherIndex"])->name("class-teachers.index");
 Route::post("/class-teachers", [\App\Http\Controllers\Admin\YearlyPlannerController::class, "classTeacherStore"])->name("class-teachers.store");
 Route::delete("/class-teachers/{roster}",[\App\Http\Controllers\Admin\YearlyPlannerController::class, "classTeacherDestroy"])->name("class-teachers.destroy");

 // Staff history
 Route::get("/staff-history/{staff}", [\App\Http\Controllers\Admin\YearlyPlannerController::class, "staffHistory"])->name("staff-history");
 Route::post("/staff-history/{staff}", [\App\Http\Controllers\Admin\YearlyPlannerController::class, "staffHistoryStore"])->name("staff-history.store");

 // Student movements
 Route::get("/movements", [\App\Http\Controllers\Admin\YearlyPlannerController::class, "studentMovementIndex"])->name("movements.index");
 Route::post("/movements/{student}/move", [\App\Http\Controllers\Admin\YearlyPlannerController::class, "studentMove"])->name("movements.move");
 });
 Route::post("/backups/prune", [\App\Http\Controllers\Admin\BackupController::class, "prune"])->name("backups.prune");

 // Fee Reminders
 Route::get("/fee-reminders", [\App\Http\Controllers\Admin\FeeReminderController::class, "index"])->name("fee-reminders.index");
 Route::get("/fee-reminders/history", [\App\Http\Controllers\Admin\FeeReminderController::class, "history"])->name("fee-reminders.history");
 Route::post("/fee-reminders/send", [\App\Http\Controllers\Admin\FeeReminderController::class, "send"])->name("fee-reminders.send");
 Route::post("/fee-reminders/send-all", [\App\Http\Controllers\Admin\FeeReminderController::class, "sendAll"])->name("fee-reminders.send-all");

 // Salary Structures
 Route::get("/salary-structures", [\App\Http\Controllers\Admin\SalaryStructureController::class, "index"])->name("salary-structures.index");
 Route::get("/salary-structures/create", [\App\Http\Controllers\Admin\SalaryStructureController::class, "create"])->name("salary-structures.create");
 Route::post("/salary-structures", [\App\Http\Controllers\Admin\SalaryStructureController::class, "store"])->name("salary-structures.store");
 Route::get("/salary-structures/{salaryStructure}/edit", [\App\Http\Controllers\Admin\SalaryStructureController::class, "edit"])->name("salary-structures.edit");
 Route::put("/salary-structures/{salaryStructure}", [\App\Http\Controllers\Admin\SalaryStructureController::class, "update"])->name("salary-structures.update");
 Route::delete("/salary-structures/{salaryStructure}", [\App\Http\Controllers\Admin\SalaryStructureController::class, "destroy"])->name("salary-structures.destroy");

 // Payroll Runs
 Route::get("/payroll-runs", [\App\Http\Controllers\Admin\PayrollController::class, "index"])->name("payroll-runs.index");
 Route::get("/payroll-runs/create", [\App\Http\Controllers\Admin\PayrollController::class, "create"])->name("payroll-runs.create");
 Route::post("/payroll-runs", [\App\Http\Controllers\Admin\PayrollController::class, "store"])->name("payroll-runs.store");
 Route::get("/payroll-runs/{run}", [\App\Http\Controllers\Admin\PayrollController::class, "show"])->name("payroll-runs.show");
 Route::post("/payroll-runs/{run}/approve", [\App\Http\Controllers\Admin\PayrollController::class, "approve"])->name("payroll-runs.approve");
 Route::post("/payroll-runs/{run}/mark-paid", [\App\Http\Controllers\Admin\PayrollController::class, "markPaid"])->name("payroll-runs.mark-paid");
 Route::post("/payroll-runs/{run}/cancel", [\App\Http\Controllers\Admin\PayrollController::class, "cancel"])->name("payroll-runs.cancel");
 Route::delete("/payroll-runs/{run}", [\App\Http\Controllers\Admin\PayrollController::class, "destroy"])->name("payroll-runs.destroy");
 Route::post("/payroll-items/{item}/pay", [\App\Http\Controllers\Admin\PayrollController::class, "payItem"])->name("payroll-items.pay");

 // Exports
 Route::get("/exports", [\App\Http\Controllers\Admin\ExportController::class, "index"])->name("exports.index");
 Route::get("/exports/students/excel", [\App\Http\Controllers\Admin\ExportController::class, "studentsExcel"])->name("exports.students.excel");
 Route::get("/exports/students/pdf", [\App\Http\Controllers\Admin\ExportController::class, "studentsPdf"])->name("exports.students.pdf");
 Route::get("/exports/staff/excel", [\App\Http\Controllers\Admin\ExportController::class, "staffExcel"])->name("exports.staff.excel");
 Route::get("/exports/staff/pdf", [\App\Http\Controllers\Admin\ExportController::class, "staffPdf"])->name("exports.staff.pdf");
 Route::get("/exports/payments/excel", [\App\Http\Controllers\Admin\ExportController::class, "paymentsExcel"])->name("exports.payments.excel");
 Route::get("/exports/payments/pdf", [\App\Http\Controllers\Admin\ExportController::class, "paymentsPdf"])->name("exports.payments.pdf");
 Route::get("/exports/attendance/excel", [\App\Http\Controllers\Admin\ExportController::class, "attendanceExcel"])->name("exports.attendance.excel");
 Route::get("/exports/attendance/pdf", [\App\Http\Controllers\Admin\ExportController::class, "attendancePdf"])->name("exports.attendance.pdf");
 Route::get("/exports/sms/excel", [\App\Http\Controllers\Admin\ExportController::class, "smsExcel"])->name("exports.sms.excel");
 Route::get("/exports/sms/pdf", [\App\Http\Controllers\Admin\ExportController::class, "smsPdf"])->name("exports.sms.pdf");
 Route::get("/exports/results/excel", [\App\Http\Controllers\Admin\ExportController::class, "resultsExcel"])->name("exports.results.excel");
 Route::post("/settings/school", [SchoolSettingsController::class, "update"])->name("settings.school.update");

 Route::resource("subjects", SubjectController::class);

        // Departments
        Route::resource("departments", \App\Http\Controllers\Admin\DepartmentController::class);

        // Departments
        Route::resource("departments", \App\Http\Controllers\Admin\DepartmentController::class);
 });

 Route::resource("students", StudentController::class);
 Route::post("/students/{student}/enable-login", [StudentController::class, "enableLogin"])->name("students.enable-login");
 Route::post("/students/{student}/disable-login", [StudentController::class, "disableLogin"])->name("students.disable-login");
 Route::post("/students/bulk-enable", [StudentController::class, "bulkEnable"])->name("students.bulk-enable");
 Route::post("/students/bulk-disable", [StudentController::class, "bulkDisable"])->name("students.bulk-disable");
 Route::post("/students/enable-all", [StudentController::class, "enableAll"])->name("students.enable-all");
 Route::post("/students/disable-all", [StudentController::class, "disableAll"])->name("students.disable-all");
 Route::post("/students/{student}/enable-login", [StudentController::class, "enableLogin"])->name("students.enable-login");
 Route::post("/students/{student}/disable-login", [StudentController::class, "disableLogin"])->name("students.disable-login");
 Route::resource("staff", StaffController::class);
    Route::get("/api/subjects-by-department", [StaffController::class, "subjectsByDepartment"])->name("staff.subjects.byDepartment");
    Route::post("/staff/{staff}/resend-credentials", [StaffController::class, "resendCredentials"])->name("staff.resend-credentials");
    Route::post("/staff/{staff}/reset-password",     [StaffController::class, "resetPassword"])->name("staff.reset-password");

 Route::get("/attendance", [AttendanceController::class, "index"])->name("attendance.index");
 Route::get("/attendance/create", [AttendanceController::class, "create"])->name("attendance.create");
 Route::post("/attendance", [AttendanceController::class, "store"])->name("attendance.store");

 Route::get("/emergencies", [EmergencyController::class, "index"])->name("emergencies.index");
 Route::get("/emergencies/create", [EmergencyController::class, "create"])->name("emergencies.create");
 Route::post("/emergencies", [EmergencyController::class, "store"])->name("emergencies.store");

 Route::get("/results", [ResultController::class, "index"])->name("results.index");
 Route::get("/results/create", [ResultController::class, "create"])->name("results.create");
 Route::post("/results", [ResultController::class, "store"])->name("results.store");
 Route::post("/results/publish/{exam}", [ResultController::class, "publish"])->name("results.publish");
 Route::get("/results/{exam}", [ResultController::class, "show"])->name("results.show");

 Route::get("/communication", [CommunicationController::class, "index"])->name("communication.index");
 Route::get("/communication/create", [CommunicationController::class, "create"])->name("communication.create");
 Route::post("/communication/send", [CommunicationController::class, "send"])
 ->middleware("throttle:sms-bulk")
 ->name("communication.send");
 // Duty Rosters
 Route::resource("duty-rosters", \App\Http\Controllers\Admin\DutyRosterController::class);

 // Announcements
 Route::get("/announcements", [\App\Http\Controllers\Admin\AnnouncementController::class, "index"])->name("announcements.index");
 Route::get("/announcements/create", [\App\Http\Controllers\Admin\AnnouncementController::class, "create"])->name("announcements.create");
 Route::post("/announcements", [\App\Http\Controllers\Admin\AnnouncementController::class, "store"])->name("announcements.store");
 Route::delete("/announcements/{announcement}", [\App\Http\Controllers\Admin\AnnouncementController::class, "destroy"])->name("announcements.destroy");

 // ============== FINANCE ==============
 // Fee Structures
 Route::resource("fee-structures", \App\Http\Controllers\Admin\FeeStructureController::class);

 // Invoices
 Route::get("/invoices", [\App\Http\Controllers\Admin\InvoiceController::class, "index"])->name("invoices.index");
 Route::get("/invoices/create", [\App\Http\Controllers\Admin\InvoiceController::class, "create"])->name("invoices.create");
 Route::post("/invoices", [\App\Http\Controllers\Admin\InvoiceController::class, "store"])->name("invoices.store");
 Route::post("/invoices/generate", [\App\Http\Controllers\Admin\InvoiceController::class, "generate"])->name("invoices.generate");
 Route::get("/invoices/{invoice}", [\App\Http\Controllers\Admin\InvoiceController::class, "show"])->name("invoices.show");
 Route::delete("/invoices/{invoice}", [\App\Http\Controllers\Admin\InvoiceController::class, "destroy"])->name("invoices.destroy");

 // Payments
 Route::get("/payments", [\App\Http\Controllers\Admin\PaymentController::class, "index"])->name("payments.index");
 Route::get("/payments/create", [\App\Http\Controllers\Admin\PaymentController::class, "create"])->name("payments.create");
 Route::post("/payments", [\App\Http\Controllers\Admin\PaymentController::class, "store"])->name("payments.store");
});