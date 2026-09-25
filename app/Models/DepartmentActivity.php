<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentActivity extends Model {
 use HasFactory;

 protected $fillable = [
 "school_id","department","staff_id","title","description",
 "activity_type","activity_date","status","priority",
 "outcome","notes","approved_by","approved_at",
 ];

 protected $casts = [
 "activity_date" => "date",
 "approved_at" => "datetime",
 ];

 public function staff() { return $this->belongsTo(Staff::class); }
 public function approver() { return $this->belongsTo(User::class, "approved_by"); }

 public static function departments(): array
 {
 return [
 "Academic",
 "Discipline",
 "Sports",
 "Health",
 "Boarding",
 "Guidance",
 "Library",
 "Environment",
 "Security",
 "Feeding",
 "Administration",
 ];
 }

 public function statusColor(): string
 {
 return match ($this->status) {
 "completed" => "green",
 "in_progress" => "blue",
 "cancelled" => "red",
 default => "yellow",
 };
 }

 public function priorityColor(): string
 {
 return match ($this->priority) {
 "urgent" => "red",
 "high" => "orange",
 "low" => "gray",
 default => "blue",
 };
 }
}