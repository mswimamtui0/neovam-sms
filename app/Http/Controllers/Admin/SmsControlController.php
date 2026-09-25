<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsControlSetting;
use App\Models\SmsLog;
use App\Models\SmsSkippedMessage;
use App\Models\SmsTriggerSetting;
use Illuminate\Http\Request;

class SmsControlController extends Controller
{
 public function index()
 {
 $settings = SmsControlSetting::orderBy("key")->get()->keyBy("key");

 $stats = [
 "master" => SmsControlSetting::masterEnabled(),
 "test_mode" => SmsControlSetting::testMode(),
 "schedule" => SmsControlSetting::scheduleEnabled(),
 "sms_today" => SmsLog::whereDate("created_at", now()->toDateString())->count(),
 "sms_hour" => SmsLog::where("created_at", ">=", now()->subHour())->count(),
 "skipped_today"=> SmsSkippedMessage::whereDate("created_at", now()->toDateString())->count(),
 "enabled" => SmsTriggerSetting::where("is_enabled", true)->count(),
 "disabled" => SmsTriggerSetting::where("is_enabled", false)->count(),
 ];

 $triggersByCategory = SmsTriggerSetting::orderBy("category")->get()->groupBy("category");

 return view("admin.sms-control.index", compact("settings","stats","triggersByCategory"));
 }

 public function update(Request $request)
 {
 $data = $request->validate([
 "master_switch" => "nullable|boolean",
 "test_mode" => "nullable|boolean",
 "schedule_enabled" => "nullable|boolean",
 "schedule_start" => "nullable|string|max:5",
 "schedule_end" => "nullable|string|max:5",
 "schedule_days" => "nullable|array",
 "schedule_days.*" => "in:1,2,3,4,5,6,7",
 "rate_limit_hour" => "nullable|integer|min:1|max:10000",
 "rate_limit_day" => "nullable|integer|min:1|max:100000",
 "default_language" => "nullable|in:en,sw,both",
 ]);

 SmsControlSetting::set("master_switch", $request->boolean("master_switch") ? 1 : 0);
 SmsControlSetting::set("test_mode", $request->boolean("test_mode") ? 1 : 0);
 SmsControlSetting::set("schedule_enabled", $request->boolean("schedule_enabled") ? 1 : 0);

 if ($request->filled("schedule_start")) SmsControlSetting::set("schedule_start", $data["schedule_start"]);
 if ($request->filled("schedule_end")) SmsControlSetting::set("schedule_end", $data["schedule_end"]);

 SmsControlSetting::set("schedule_days", $data["schedule_days"] ?? []);
 SmsControlSetting::set("rate_limit_hour", $data["rate_limit_hour"] ?? 200);
 SmsControlSetting::set("rate_limit_day", $data["rate_limit_day"] ?? 2000);
 SmsControlSetting::set("default_language", $data["default_language"] ?? "en");

 return back()->with("success","SMS control settings saved.");
 }

 /**
 * Quick preset — apply one of the built-in configs.
 */
 public function preset(Request $request)
 {
 $data = $request->validate([
 "preset" => "required|in:all_on,all_off,essentials_only,parents_only,staff_only,silent,test",
 ]);

 $preset = $data["preset"];

 // Reset all triggers
 SmsTriggerSetting::where("is_critical", false)->update(["is_enabled" => true]);

 switch ($preset) {
 case "all_on":
 SmsControlSetting::set("master_switch", 1);
 SmsControlSetting::set("test_mode", 0);
 break;

 case "all_off":
 case "silent":
 SmsControlSetting::set("master_switch", 0);
 SmsControlSetting::set("test_mode", 0);
 break;

 case "essentials_only":
 SmsControlSetting::set("master_switch", 1);
 SmsControlSetting::set("test_mode", 0);
 // Only keep essentials
 $essentials = ["absence","result","payment","emergency","fee_reminder","school_closure","password_reset"];
 SmsTriggerSetting::where("is_critical", false)
 ->whereNotIn("trigger_key", $essentials)
 ->update(["is_enabled" => false]);
 break;

 case "parents_only":
 SmsControlSetting::set("master_switch", 1);
 $staffOnly = ["department_change","staff_transfer","role_change","class_teacher_change",
 "leave_approved","leave_rejected","duty_reminder","duty_assigned","duty_missed",
 "salary_processed","login_alert"];
 SmsTriggerSetting::whereIn("trigger_key", $staffOnly)->update(["is_enabled" => false]);
 break;

 case "staff_only":
 SmsControlSetting::set("master_switch", 1);
 $parentOnly = ["absence","absence_repeat","result","promotion","graduation","payment",
 "fee_reminder","fee_overdue","invoice_created","discipline_warning",
 "discipline_praise","suspension","library_overdue"];
 SmsTriggerSetting::whereIn("trigger_key", $parentOnly)->update(["is_enabled" => false]);
 break;

 case "test":
 SmsControlSetting::set("master_switch", 1);
 SmsControlSetting::set("test_mode", 1);
 break;
 }

 return back()->with("success","Preset applied: " . str_replace("_"," ", $preset));
 }

 /**
 * View skipped messages.
 */
 public function skipped()
 {
 $skipped = SmsSkippedMessage::latest()->paginate(50);

 $byReason = SmsSkippedMessage::selectRaw("skip_reason, COUNT(*) as count")
 ->groupBy("skip_reason")
 ->pluck("count", "skip_reason")->toArray();

 return view("admin.sms-control.skipped", compact("skipped","byReason"));
 }

 /**
 * Clear skipped messages log.
 */
 public function clearSkipped()
 {
 SmsSkippedMessage::truncate();
 return back()->with("success","Skipped messages log cleared.");
 }
}