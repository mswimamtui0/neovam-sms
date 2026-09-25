<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DutyReport extends Model {
 use HasFactory;
 protected $fillable = [
 "staff_id","report_type","report_date",
 "morning_notes","break_notes","lunch_notes","evening_notes","night_notes",
 "incidents_count","summary","handover","status",
 ];
 protected $casts = ["report_date" => "date"];
 public function staff() { return $this->belongsTo(Staff::class); }
}