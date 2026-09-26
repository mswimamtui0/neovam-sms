<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class SportsRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "sports";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","sport_name","team_name","event_type","opponent","venue","result","notes","student_id"];
}