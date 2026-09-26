<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class FinanceRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "finance";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","type","student_id","student_name","amount","currency","description","reference_no","status","parent_notified"];
    protected $casts = ["amount"=>"decimal:2","parent_notified"=>"boolean"];
}