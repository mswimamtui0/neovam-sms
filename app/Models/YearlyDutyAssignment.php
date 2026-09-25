<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearlyDutyAssignment extends Model {
 use HasFactory;
 protected $fillable = [
 "calendar_id","staff_id","duty_date","duty_type","shift",
 "location","notes","sms_sent",
 ];
 protected $casts = ["duty_date" => "date","sms_sent" => "boolean"];
 public function calendar() { return $this->belongsTo(YearlyDutyCalendar::class, "calendar_id"); }
 public function staff() { return $this->belongsTo(Staff::class); }
}