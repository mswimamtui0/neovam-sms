<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherReport extends Model {
    use HasFactory;
    protected $fillable = ["staff_id","report_type","week_start","week_end","summary","challenges","next_plan","status"];
    protected $casts = ["week_start" => "date","week_end" => "date"];
    public function staff() { return $this->belongsTo(Staff::class); }
}