<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTriggerSetting extends Model {
 use HasFactory;

 protected $fillable = [
 "school_id","trigger_key","label","category","description",
 "is_enabled","is_critical",
 ];

 protected $casts = [
 "is_enabled" => "boolean",
 "is_critical" => "boolean",
 ];

 /**
 * Check if a trigger is enabled.
 * Critical triggers always return true.
 */
 public static function enabled(string $key): bool
 {
 $setting = self::where("trigger_key", $key)->first();

 if (!$setting) return true; // default = enabled
 if ($setting->is_critical) return true; // critical always on

 return $setting->is_enabled;
 }
}