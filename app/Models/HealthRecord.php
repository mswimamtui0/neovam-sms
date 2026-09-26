<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "health";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","student_id","student_name","class_name","type","severity","symptoms","treatment","notes","status","parent_notified"];
    protected $casts = ["parent_notified" => "boolean"];
}