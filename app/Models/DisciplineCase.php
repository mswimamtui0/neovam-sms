<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class DisciplineCase extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "discipline";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","student_id","student_name","class_name","category","offence","description","action_taken","status","parent_notified"];
    protected $casts = ["parent_notified" => "boolean"];
}