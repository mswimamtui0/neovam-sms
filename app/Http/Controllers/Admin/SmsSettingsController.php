<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Sms\NeovamGateway;
use App\Services\Sms\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SmsSettingsController extends Controller
{
 public function index(NeovamGateway $gateway)
 {
 $balance = $gateway->balance();

 return view("admin.settings.sms", [
 "url" => config("services.neovam_sms.url"),
 "key" => config("services.neovam_sms.key") ? $this->mask(config("services.neovam_sms.key")) : null,
 "sender" => config("services.neovam_sms.sender"),
 "environment" => $gateway->environment(),
 "testMode" => $gateway->isTestMode(),
 "balance" => $balance,
 ]);
 }

 public function update(Request $request)
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

 // Clear config cache so new values take effect immediately
 \Artisan::call("config:clear");

 return back()->with("success", "SMS settings saved.");
 }

 public function test(Request $request, SmsService $sms)
 {
 $data = $request->validate([
 "phone" => "required|string|max:30",
 "message" => "required|string|max:160",
 ]);

 $sent = $sms->send($data["phone"], $data["message"], "test");

 if ($sent) {
 return back()->with("success", "Test SMS dispatched to {$data["phone"]}. Check /admin/communication for the log.");
 }

 return back()->with("error", "Test SMS failed. Check the log at /admin/communication or storage/logs/laravel.log.");
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

 // If line exists, replace it
 if (preg_match("/^{$key}=.*$/m", $env)) {
 return preg_replace("/^{$key}=.*$/m", "{$key}={$escaped}", $env);
 }

 // Otherwise append
 return rtrim($env) . PHP_EOL . "{$key}={$escaped}" . PHP_EOL;
 }
}