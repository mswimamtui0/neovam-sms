<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use Illuminate\Http\Request;

class SmsDeliveryController extends Controller
{
 public function index(Request $request)
 {
 $query = SmsLog::latest();

 if ($request->filled("status")) {
 $query->where("status", $request->status);
 }
 if ($request->filled("delivery_status")) {
 $query->where("delivery_status", $request->delivery_status);
 }
 if ($request->filled("trigger")) {
 $query->where("trigger", $request->trigger);
 }
 if ($request->filled("date")) {
 $query->whereDate("created_at", $request->date);
 }

 $logs = $query->paginate(40)->withQueryString();

 // Summary stats
 $stats = [
 "total" => SmsLog::count(),
 "sent" => SmsLog::where("status","sent")->count(),
 "failed" => SmsLog::where("status","failed")->count(),
 "pending" => SmsLog::where("status","pending")->count(),
 "delivered" => SmsLog::where("delivery_status","delivered")->count(),
 "undelivered" => SmsLog::where("delivery_status","undelivered")->count(),
 "today" => SmsLog::whereDate("created_at", now()->toDateString())->count(),
 ];

 $triggers = SmsLog::distinct()->pluck("trigger")->filter()->sort();

 return view("admin.sms-delivery.index", compact("logs","stats","triggers"));
 }

 /**
 * Update a single SMS log's delivery status.
 */
 public function update(Request $request, SmsLog $log)
 {
 $data = $request->validate([
 "delivery_status" => "required|in:pending,delivered,undelivered,rejected,expired",
 "error_message" => "nullable|string|max:500",
 "error_code" => "nullable|string|max:50",
 "cost" => "nullable|numeric|min:0",
 "network" => "nullable|string|max:50",
 ]);

 $log->update($data);

 if ($data["delivery_status"] === "delivered") {
 $log->update(["delivered_at" => now()]);
 } elseif (in_array($data["delivery_status"], ["undelivered","rejected","expired"])) {
 $log->update(["failed_at" => now()]);
 }

 return back()->with("success","Delivery status updated.");
 }

 /**
 * Webhook endpoint for the SMS gateway to POST delivery receipts.
 */
 public function webhook(Request $request)
 {
 $data = $request->validate([
 "reference" => "required|string",
 "delivery_status" => "required|in:delivered,undelivered,rejected,expired",
 "error_code" => "nullable|string",
 "error_message" => "nullable|string",
 "cost" => "nullable|numeric",
 "network" => "nullable|string",
 ]);

 $log = SmsLog::where("gateway_ref", $data["reference"])->first();

 if (!$log) {
 return response()->json(["success" => false, "error" => "reference_not_found"], 404);
 }

 $log->update([
 "delivery_status" => $data["delivery_status"],
 "error_code" => $data["error_code"] ?? null,
 "error_message" => $data["error_message"] ?? null,
 "cost" => $data["cost"] ?? $log->cost,
 "network" => $data["network"] ?? $log->network,
 "delivered_at" => $data["delivery_status"] === "delivered" ? now() : $log->delivered_at,
 "failed_at" => in_array($data["delivery_status"], ["undelivered","rejected","expired"]) ? now() : $log->failed_at,
 ]);

 return response()->json(["success" => true]);
 }

 /**
 * Bulk retry failed SMS.
 */
 public function retry(Request $request)
 {
 $data = $request->validate([
 "ids" => "required|array|min:1",
 "ids.*" => "exists:sms_logs,id",
 ]);

 $sms = app(\App\Services\Sms\SmsService::class);

 $retried = 0;
 $succeeded = 0;

 foreach (SmsLog::whereIn("id", $data["ids"])->where("status", "failed")->get() as $log) {
 $retried++;
 if ($sms->send($log->recipient, $log->message, $log->trigger . "_retry")) {
 $succeeded++;
 }
 }

 return back()->with("success", "Retried {$retried} SMS — {$succeeded} succeeded.");
 }
}