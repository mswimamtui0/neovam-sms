<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory, BelongsToSchool;

    protected $table = "classrooms";

    protected $fillable = [
        "school_id", "class_teacher_id", "name", "level", "stream",
    ];

    /**
     * Students in this class.
     * Explicitly set foreign key to "classroom_id" (not class_room_id).
     */
    public function students()
    {
        return $this->hasMany(Student::class, "classroom_id", "id");
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, "classroom_id", "id");
    }

    public function classTeacher()
    {
        return $this->belongsTo(Staff::class, "class_teacher_id");
    }

    public function syllabusCoverage()
    {
        return $this->hasMany(SyllabusCoverage::class, "classroom_id", "id");
    }

    public function subjectAssignments()
    {
        return $this->hasMany(SubjectAssignment::class, "classroom_id", "id");
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, "classroom_id", "id");
    }

    public function meetings()
    {
        return $this->hasMany(ClassMeetingNote::class, "classroom_id", "id");
    }
}