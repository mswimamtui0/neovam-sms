<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class PsychosocialRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "counselling";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","student_name","class_name","session_type","summary","follow_up","referral","status","private"];
    protected $casts = ["private"=>"boolean"];
}