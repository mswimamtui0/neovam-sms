<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class HrRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "hr";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","type","staff_name","staff_no","subject","description","start_date","end_date","resolution","status"];
    protected $casts = ["start_date"=>"date","end_date"=>"date"];
}