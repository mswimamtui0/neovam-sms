<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentContact extends Model {
 use HasFactory;
 protected $fillable = ["staff_id","student_id","contact_type","reason","outcome"];
 public function staff() { return $this->belongsTo(Staff::class); }
 public function student() { return $this->belongsTo(Student::class); }
}