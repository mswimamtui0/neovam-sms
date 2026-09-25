<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsBalanceTransaction;
use App\Models\SmsControlSetting;
use App\Models\SmsLog;
use App\Models\SmsPricingRule;
use App\Models\SmsSkippedMessage;
use App\Models\SmsTemplate;
use App\Models\SmsTriggerSetting;
use App\Services\Sms\NeovamGateway;
use App\Services\Sms\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SmsSettingsHubController extends Controller
{
 /**
 * Main SMS Settings panel — one page, collapsible sections.
 */
 public function index()
 {
 // Control Center
 $controlSettings = SmsControlSetting::orderBy("key")->get()->keyBy("key");
 $controlStats = [
 "master" => SmsControlSetting::masterEnabled(),
 "test_mode" => SmsControlSetting::testMode(),
 "schedule" => SmsControlSetting::scheduleEnabled(),
 "sms_today" => SmsLog::whereDate("created_at", now()->toDateString())->count(),
 "sms_hour" => SmsLog::where("created_at", ">=", now()->subHour())->count(),
 "skipped_today" => SmsSkippedMessage::whereDate("created_at", now()->toDateString())->count(),
 "enabled" => SmsTriggerSetting::where("is_enabled", true)->count(),
 "disabled" => SmsTriggerSetting::where("is_enabled", false)->count(),
 ];

 // Gateway
 $gateway = app(NeovamGateway::class);
 $gatewayData = [
 "url" => config("services.neovam_sms.url"),
 "key" => config("services.neovam_sms.key") ? $this->mask(config("services.neovam_sms.key")) : null,
 "sender" => config("services.neovam_sms.sender"),
 "environment" => $gateway->environment(),
 "testMode" => $gateway->isTestMode(),
 "balance" => $gateway->balance(),
 ];

 // Templates
 $templates = SmsTemplate::orderBy("key")->orderBy("language")->paginate(40, ["*"], "templates_page");
 $templateKeys = SmsTemplate::distinct()->pluck("key")->sort();

 // Triggers
 $triggersByCategory = SmsTriggerSetting::orderBy("category")->get()->groupBy("category");

 // Delivery
 $deliveryLogs = SmsLog::latest()->paginate(30, ["*"], "delivery_page");
 $deliveryStats = [
 "total" => SmsLog::count(),
 "sent" => SmsLog::where("status","sent")->count(),
 "failed" => SmsLog::where("status","failed")->count(),
 "pending" => SmsLog::where("status","pending")->count(),
 "delivered" => SmsLog::where("delivery_status","delivered")->count(),
 "undelivered" => SmsLog::where("delivery_status","undelivered")->count(),
 "today" => SmsLog::whereDate("created_at", now()->toDateString())->count(),
 ];

 // Cost
 $costData = [
 "balanceUnits" => SmsBalanceTransaction::balanceUnits(),
 "balanceMoney" => SmsBalanceTransaction::balanceMoney(),
 "pricing" => SmsPricingRule::current(),
 "summary" => \App\Services\Sms\SmsCostService::summary(now()->startOfMonth(), now()->endOfMonth()),
 ];

 // Skipped
 $skipped = SmsSkippedMessage::latest()->paginate(30, ["*"], "skipped_page");
 $skippedReasons = SmsSkippedMessage::selectRaw("skip_reason, COUNT(*) as count")
 ->groupBy("skip_reason")
 ->pluck("count", "skip_reason")->toArray();

 return view("admin.sms-settings.index", compact(
 "controlSettings","controlStats",
 "gatewayData",
 "templates","templateKeys",
 "triggersByCategory",
 "deliveryLogs","deliveryStats",
 "costData",
 "skipped","skippedReasons"
 ));
 }

 /* ============ QUICK ACTIONS ============ */

 public function masterToggle(Request $request)
 {
 $data = $request->validate([
 "enabled" => "required|boolean",
 ]);

 SmsControlSetting::set("master_switch", $data["enabled"] ? 1 : 0);

 return back()->with("success", "Master SMS " . ($data["enabled"] ? "enabled" : "disabled") . ".");
 }

 public function testMode(Request $request)
 {
 $data = $request->validate([
 "enabled" => "required|boolean",
 ]);

 SmsControlSetting::set("test_mode", $data["enabled"] ? 1 : 0);

 return back()->with("success", "Test mode " . ($data["enabled"] ? "enabled" : "disabled") . ".");
 }

 public function updateControl(Request $request)
 {
 $data = $request->validate([
 "schedule_enabled" => "nullable|boolean",
 "schedule_start" => "nullable|string|max:5",
 "schedule_end" => "nullable|string|max:5",
 "schedule_days" => "nullable|array",
 "rate_limit_hour" => "nullable|integer|min:1|max:10000",
 "rate_limit_day" => "nullable|integer|min:1|max:100000",
 "default_language" => "nullable|in:en,sw,both",
 ]);

 SmsControlSetting::set("schedule_enabled", $request->boolean("schedule_enabled") ? 1 : 0);
 if ($request->filled("schedule_start")) SmsControlSetting::set("schedule_start", $data["schedule_start"]);
 if ($request->filled("schedule_end")) SmsControlSetting::set("schedule_end", $data["schedule_end"]);
 SmsControlSetting::set("schedule_days", $data["schedule_days"] ?? []);
 SmsControlSetting::set("rate_limit_hour", $data["rate_limit_hour"] ?? 200);
 SmsControlSetting::set("rate_limit_day", $data["rate_limit_day"] ?? 2000);
 SmsControlSetting::set("default_language", $data["default_language"] ?? "en");

 return back()->with("success","SMS control settings saved.");
 }

 public function preset(Request $request)
 {
 $data = $request->validate([
 "preset" => "required|in:all_on,all_off,essentials_only,parents_only,staff_only,silent,test",
 ]);

 $preset = $data["preset"];
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

 return back()->with("success","Preset applied.");
 }

 public function gatewayUpdate(Request $request)
 {
 $data = $request->validate([
 "url" => "nullable|string|max:255",
 "key" => "nullable|string|max:255",
 "sender" => "nullable|string|max:50",
 "environment" => "required|in:sandbox,production",
 "test_mode" => "nullable|boolean",
 ]);

 $envPath = base_path(".env");
 $env = File::get($envPath);

 $env = $this->setEnv($env, "NEOVAM_SMS_URL", $data["url"] ?? "");
 $env = $this->setEnv($env, "NEOVAM_SMS_KEY", $data["key"] ?? "");
 $env = $this->setEnv($env, "NEOVAM_SMS_SENDER", $data["sender"] ?? "NEOVAM");
 $env = $this->setEnv($env, "NEOVAM_SMS_ENV", $data["environment"]);
 $env = $this->setEnv($env, "NEOVAM_SMS_TEST_MODE", $request->boolean("test_mode") ? "true" : "false");

 File::put($envPath, $env);
 \Artisan::call("config:clear");

 return back()->with("success","Gateway settings saved.");
 }

 public function sendTest(Request $request, SmsService $sms)
 {
 $data = $request->validate([
 "phone" => "required|string|max:30",
 "message" => "required|string|max:160",
 ]);

 $sent = $sms->send($data["phone"], $data["message"], "test");

 return back()->with(
 $sent ? "success" : "error",
 $sent ? "Test SMS dispatched to {$data["phone"]}." : "Test SMS failed — check logs."
 );
 }

 public function templateUpdate(Request $request, SmsTemplate $template)
 {
 $data = $request->validate([
 "name" => "required|string|max:150",
 "body" => "required|string|max:600",
 "description" => "nullable|string|max:255",
 "is_active" => "nullable|boolean",
 ]);

 $data["is_active"] = $request->boolean("is_active");
 $template->update($data);

 return back()->with("success","Template updated.");
 }

 public function triggerToggle(Request $request, SmsTriggerSetting $trigger)
 {
 if ($trigger->is_critical) {
 return back()->with("error","Critical triggers cannot be disabled.");
 }

 $trigger->update(["is_enabled" => $request->boolean("is_enabled")]);

 return back()->with("success", "{$trigger->label} " . ($trigger->is_enabled ? "enabled" : "disabled") . ".");
 }

 public function triggerBulk(Request $request)
 {
 $data = $request->validate([
 "category" => "required|string",
 "enable" => "required|boolean",
 ]);

 SmsTriggerSetting::where("category", $data["category"])
 ->where("is_critical", false)
 ->update(["is_enabled" => $data["enable"]]);

 return back()->with("success","Category updated.");
 }

 public function topup(Request $request)
 {
 $data = $request->validate([
 "units" => "required|integer|min:1",
 "amount" => "required|numeric|min:0",
 "reference" => "nullable|string|max:100",
 "description" => "nullable|string|max:255",
 ]);

 \App\Services\Sms\SmsCostService::topup(
 $data["units"], (float) $data["amount"],
 $data["reference"] ?? null, $data["description"] ?? null
 );

 return back()->with("success","Balance topped up.");
 }

 public function clearSkipped()
 {
 SmsSkippedMessage::truncate();
 return back()->with("success","Skipped messages log cleared.");
 }

 protected function mask(string $value): string
 {
 $len = strlen($value);
 if ($len <= 8) return str_repeat("*", $len);
 return substr($value, 0, 4) . str_repeat("*", max(0, $len - 8)) . substr($value, -4);
 }

 protected function setEnv(string $env, string $key, string $value): string
 {
 $escaped = trim($value);
 if (preg_match("/^{$key}=.*$/m", $env)) {
 return preg_replace("/^{$key}=.*$/m", "{$key}={$escaped}", $env);
 }
 return rtrim($env) . PHP_EOL . "{$key}={$escaped}" . PHP_EOL;
 }
}