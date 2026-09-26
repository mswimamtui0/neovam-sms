<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class SecurityLog extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "security";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","type","visitor_name","visitor_phone","purpose","description","status"];
}