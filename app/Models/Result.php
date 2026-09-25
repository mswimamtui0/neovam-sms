<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
 use HasFactory;

 protected $fillable = [
 'exam_id', 'student_id',
 'subject', 'marks', 'grade', 'sms_sent',
 ];

 protected $casts = [
 'sms_sent' => 'boolean',
 ];

 public function exam()
 {
 return $this->belongsTo(Exam::class);
 }

 public function student()
 {
 return $this->belongsTo(Student::class);
 }
}