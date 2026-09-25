<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function () { return view("welcome"); });

Route::get("/dashboard", function () {
 $user = auth()->user();
 if ($user->hasRole("admin") || $user->hasRole("head_of_school")) {
 return redirect()->route("admin.dashboard");
 }
 if ($user->hasRole("academic_master")) {
 return redirect()->route("academic.dashboard");
 }
 if ($user->hasRole("teacher") || $user->hasRole("teacher_on_duty")) {
 return redirect()->route("teacher.dashboard");
 }
 if ($user->hasRole("parent")) return redirect()->route("parent.dashboard");
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