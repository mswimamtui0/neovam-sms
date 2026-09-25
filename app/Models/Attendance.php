<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
 use HasFactory;

 protected $fillable = [
 'student_id', 'classroom_id',
 'date', 'status', 'recorded_by', 'sms_sent',
 ];

 protected $casts = [
 'date' => 'date',
 'sms_sent' => 'boolean',
 ];

 public function student()
 {
 return $this->belongsTo(Student::class);
 }

 public function classroom()
 {
 return $this->belongsTo(ClassRoom::class);
 }
}