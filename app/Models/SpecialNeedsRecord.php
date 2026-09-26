<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class SpecialNeedsRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "special_needs";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","student_name","class_name","need_type","description","support_plan","progress_notes","status"];
}