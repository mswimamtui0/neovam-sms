<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class MusicRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "music";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","type","title","venue","event_date","event_time","participant_count","instruments_used","notes","status"];
    protected $casts = ["event_date"=>"date"];
}