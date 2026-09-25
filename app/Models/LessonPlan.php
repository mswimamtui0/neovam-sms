<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonPlan extends Model {
 use HasFactory;
 protected $fillable = ["staff_id","classroom_id","subject_id","date","topic","objectives","activities","materials","status"];
 protected $casts = ["date" => "date"];
 public function staff() { return $this->belongsTo(Staff::class); }
 public function classroom() { return $this->belongsTo(ClassRoom::class); }
 public function subject() { return $this->belongsTo(Subject::class); }
}