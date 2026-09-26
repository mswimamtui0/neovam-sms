<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class ProcurementRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "procurement";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","type","item_name","supplier","quantity","unit","unit_price","total_amount","currency","expected_on","received_on","notes","status"];
    protected $casts = ["unit_price"=>"decimal:2","total_amount"=>"decimal:2","expected_on"=>"date","received_on"=>"date"];
}