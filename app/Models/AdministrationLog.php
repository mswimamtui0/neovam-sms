<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class AdministrationLog extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "administration";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","type","title","body","audience","status"];
}