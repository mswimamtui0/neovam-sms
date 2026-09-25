<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicMeeting extends Model {
 use HasFactory;
 protected $fillable = ["title","meeting_type","meeting_date","meeting_time","venue","agenda","minutes","status"];
 protected $casts = ["meeting_date"=>"date"];
}