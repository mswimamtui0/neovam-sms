<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model {
 use HasFactory;
 protected $fillable = ["term","year","term_start","term_end","exam_start","exam_end","notes"];
 protected $casts = ["term_start"=>"date","term_end"=>"date","exam_start"=>"date","exam_end"=>"date"];
}