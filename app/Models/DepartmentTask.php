<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentTask extends Model {
 use HasFactory;
 protected $fillable = ["staff_id","department","title","description","due_date","status","priority"];
 protected $casts = ["due_date" => "date"];
 public function staff() { return $this->belongsTo(Staff::class); }
}