<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsControlSetting extends Model {
 use HasFactory;

 protected $fillable = ["school_id","key","label","value","type","description"];

 /**
 * Get a setting by key.
 */
 public static function get(string $key, $default = null)
 {
 $setting = self::where("key", $key)->first();
 if (!$setting) return $default;

 return match ($setting->type) {
 "boolean" => (bool) $setting->value,
 "integer" => (int) $setting->value,
 "json" => json_decode($setting->value, true),
 default => $setting->value,
 };
 }

 /**
 * Set a setting.
 */
 public static function set(string $key, $value): void
 {
 $setting = self::where("key", $key)->first();
 if (!$setting) return;

 if (is_array($value)) $value = json_encode($value);

 $setting->update(["value" => (string) $value]);
 }

 /* ============ Shorthand ============ */
 public static function masterEnabled(): bool
 {
 return self::get("master_switch", true);
 }

 public static function testMode(): bool
 {
 return self::get("test_mode", false);
 }

 public static function scheduleEnabled(): bool
 {
 return self::get("schedule_enabled", false);
 }

 public static function insideScheduleWindow(): bool
 {
 if (!self::scheduleEnabled()) return true;

 $now = now();
 $startTime = self::get("schedule_start", "07:00");
 $endTime = self::get("schedule_end", "20:00");
 $days = self::get("schedule_days", ["1","2","3","4","5"]);

 if (!in_array((string) $now->dayOfWeekIso, $days)) return false;

 $current = $now->format("H:i");
 return $current >= $startTime && $current <= $endTime;
 }

 public static function rateLimitPerHour(): int
 {
 return self::get("rate_limit_hour", 200);
 }

 public static function rateLimitPerDay(): int
 {
 return self::get("rate_limit_day", 2000);
 }
}