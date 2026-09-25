<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
 use HasFactory;

 protected $fillable = [
 "staff_id","classroom_id","subject_id",
 "day_of_week","start_time","end_time",
 "period_label","room","notes",
 ];

 public function staff() { return $this->belongsTo(Staff::class); }
 public function classroom() { return $this->belongsTo(ClassRoom::class); }
 public function subject() { return $this->belongsTo(Subject::class); }

 public static function days(): array
 {
 return ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
 }
}