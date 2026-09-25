<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAttendance extends Model {
 use HasFactory;

 protected $fillable = [
 "staff_id","attendance_date","check_in","check_out","status",
 "minutes_late","hours_worked","ip_address","device","notes",
 ];

 protected $casts = [
 "attendance_date" => "date",
 "minutes_late" => "integer",
 "hours_worked" => "integer",
 ];

 public function staff() { return $this->belongsTo(Staff::class); }

 /**
 * Check in a staff member for today.
 */
 public static function checkIn(Staff $staff, string $ip = null, string $device = null): self
 {
 $today = now()->toDateString();

 $record = self::firstOrCreate(
 ["staff_id" => $staff->id, "attendance_date" => $today],
 [
 "status" => "present",
 "ip_address" => $ip,
 "device" => $device,
 ]
 );

 if (!$record->check_in) {
 $now = now()->format("H:i:s");
 $late = self::calculateLateMinutes();

 $record->update([
 "check_in" => $now,
 "minutes_late" => $late,
 "status" => $late > 0 ? "late" : "present",
 "ip_address" => $ip,
 "device" => $device,
 ]);
 }

 return $record;
 }

 /**
 * Check out a staff member for today.
 */
 public static function checkOut(Staff $staff): ?self
 {
 $today = now()->toDateString();
 $record = self::where("staff_id", $staff->id)->where("attendance_date", $today)->first();

 if (!$record || !$record->check_in) {
 return null;
 }

 if ($record->check_out) {
 return $record;
 }

 $now = now()->format("H:i:s");

 $checkIn = \Carbon\Carbon::parse($record->check_in);
 $checkOut = \Carbon\Carbon::parse($now);
 $hours = (int) round($checkIn->diffInMinutes($checkOut) / 60);

 $record->update([
 "check_out" => $now,
 "hours_worked" => $hours,
 ]);

 return $record;
 }

 /**
 * Minutes late (based on 08:00 start — adjust as needed).
 */
 public static function calculateLateMinutes(string $expectedStart = "08:00:00"): int
 {
 $now = now();
 $expected = \Carbon\Carbon::parse($now->toDateString() . " " . $expectedStart);

 if ($now->lessThanOrEqualTo($expected)) {
 return 0;
 }

 return (int) $expected->diffInMinutes($now);
 }

 /**
 * Human-readable status color.
 */
 public function statusColor(): string
 {
 return match ($this->status) {
 "present" => "green",
 "late" => "yellow",
 "absent" => "red",
 "leave" => "blue",
 default => "gray",
 };
 }
}