<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class StoreRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "stores";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","item_name","item_code","type","quantity","unit","issued_to","supplier","reference_no","notes","status"];
}