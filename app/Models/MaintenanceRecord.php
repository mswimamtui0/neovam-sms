<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "maintenance";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","location","item","issue","description","assigned_to","priority","completed_on","parts_used","cost","status"];
    protected $casts = ["completed_on"=>"date","cost"=>"decimal:2"];
}