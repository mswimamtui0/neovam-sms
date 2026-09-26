<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class CounselingSession extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "guidance";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","student_id","student_name","session_type","summary","follow_up","status","private"];
    protected $casts = ["private" => "boolean"];
}