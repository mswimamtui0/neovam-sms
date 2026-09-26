<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class UniformRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "uniform";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","type","student_name","class_name","item","size","quantity","amount","ready_on","delivered_on","notes","status"];
    protected $casts = ["amount"=>"decimal:2","ready_on"=>"date","delivered_on"=>"date"];
}