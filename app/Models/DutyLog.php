<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class DutyLog extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "duty";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","duty_date","shift","duty_type","handover_notes","incidents","status"];
    protected $casts = ["duty_date" => "date"];
}