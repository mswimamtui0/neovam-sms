<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClassMovement extends Model {
 use HasFactory;
 protected $fillable = [
 "student_id","from_classroom_id","to_classroom_id",
 "from_level","to_level","movement_type","reason",
 "effective_date","sms_sent","moved_by",
 ];
 protected $casts = ["effective_date" => "date","sms_sent" => "boolean"];
 public function student() { return $this->belongsTo(Student::class); }
 public function fromClassroom() { return $this->belongsTo(ClassRoom::class, "from_classroom_id"); }
 public function toClassroom() { return $this->belongsTo(ClassRoom::class, "to_classroom_id"); }
 public function mover() { return $this->belongsTo(User::class, "moved_by"); }
}