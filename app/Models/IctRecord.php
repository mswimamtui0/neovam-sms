<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class IctRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "ict";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","type","title","description","reported_by","assigned_to","device","priority","resolution","status"];
}