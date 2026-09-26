<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class TransportTrip extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "transport";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","bus_code","route_name","driver_name","conductor_name","student_count","trip_date","trip_time","direction","fuel_used","notes","status"];
    protected $casts = ["trip_date"=>"date","fuel_used"=>"decimal:2"];
}