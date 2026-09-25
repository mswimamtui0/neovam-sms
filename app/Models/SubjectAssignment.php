<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectAssignment extends Model {
 use HasFactory;
 protected $fillable = ["staff_id","classroom_id","subject_id","periods_per_week"];
 public function staff() { return $this->belongsTo(Staff::class); }
 public function classroom() { return $this->belongsTo(ClassRoom::class); }
 public function subject() { return $this->belongsTo(Subject::class); }
}