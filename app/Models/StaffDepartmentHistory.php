<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffDepartmentHistory extends Model {
 use HasFactory;
 protected $table = "staff_department_history";
 protected $fillable = [
 "staff_id","from_roles","to_roles","from_department","to_department",
 "effective_date","reason","changed_by",
 ];
 protected $casts = ["effective_date" => "date"];
 public function staff() { return $this->belongsTo(Staff::class); }
 public function changer() { return $this->belongsTo(User::class, "changed_by"); }
}