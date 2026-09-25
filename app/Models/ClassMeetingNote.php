<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassMeetingNote extends Model {
 use HasFactory;
 protected $fillable = ["classroom_id","staff_id","meeting_date","topic","notes","decisions"];
 protected $casts = ["meeting_date" => "date"];
 public function classroom() { return $this->belongsTo(ClassRoom::class); }
 public function staff() { return $this->belongsTo(Staff::class); }
}