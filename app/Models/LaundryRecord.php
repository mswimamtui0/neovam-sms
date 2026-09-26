<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class LaundryRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "laundry";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","type","dorm_name","item","quantity","received_on","returned_on","notes","status"];
    protected $casts = ["received_on"=>"date","returned_on"=>"date"];
}