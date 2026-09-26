<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class AgricultureRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "agriculture";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","type","plot","crop_animal","activity","quantity","unit","notes","activity_date","status"];
    protected $casts = ["quantity"=>"decimal:2","activity_date"=>"date"];
}