<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class FrontOfficeRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "front_office";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","type","visitor_name","visitor_phone","host_name","purpose","message","visit_date","visit_time","status"];
    protected $casts = ["visit_date"=>"date"];
}