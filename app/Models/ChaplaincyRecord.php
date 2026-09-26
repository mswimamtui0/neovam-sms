<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class ChaplaincyRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "chaplaincy";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","type","title","venue","event_date","event_time","attendees","notes","status"];
    protected $casts = ["event_date"=>"date"];
}