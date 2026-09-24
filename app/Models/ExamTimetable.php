<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamTimetable extends Model {
    use HasFactory;
    protected $fillable = ["exam_id","subject_id","exam_date","start_time","end_time","venue","invigilator"];
    protected $casts = ["exam_date"=>"date"];
    public function exam()    { return $this->belongsTo(Exam::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
}