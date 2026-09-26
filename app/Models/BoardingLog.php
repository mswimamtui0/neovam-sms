<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class BoardingLog extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "boarding";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","dorm_name","type","description","student_id","status"];
}