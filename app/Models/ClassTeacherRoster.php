<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassTeacherRoster extends Model {
 use HasFactory;
 protected $fillable = [
 "school_id","classroom_id","staff_id","year","term",
 "start_date","end_date","is_active","notes","assigned_by",
 ];
 protected $casts = ["start_date" => "date","end_date" => "date","is_active" => "boolean"];
 public function classroom() { return $this->belongsTo(ClassRoom::class); }
 public function staff() { return $this->belongsTo(Staff::class); }
 public function assigner() { return $this->belongsTo(User::class, "assigned_by"); }
}