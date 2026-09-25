<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model {
 use HasFactory;

 protected $fillable = [
 "student_id","from_classroom_id","to_classroom_id",
 "from_level","to_level","academic_year",
 "status","notes","sms_sent","promoted_by","promoted_at",
 ];

 protected $casts = [
 "sms_sent" => "boolean",
 "promoted_at" => "datetime",
 ];

 public function student() { return $this->belongsTo(Student::class); }
 public function fromClassroom() { return $this->belongsTo(ClassRoom::class, "from_classroom_id"); }
 public function toClassroom() { return $this->belongsTo(ClassRoom::class, "to_classroom_id"); }
 public function promoter() { return $this->belongsTo(User::class, "promoted_by"); }
}