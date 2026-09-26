<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class EnvironmentLog extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "environment";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","area","type","description","rating","status"];
}