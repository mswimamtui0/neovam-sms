<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class DramaRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "drama";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","type","title","venue","event_date","event_time","participant_count","props","notes","status"];
    protected $casts = ["event_date"=>"date"];
}