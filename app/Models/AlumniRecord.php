<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class AlumniRecord extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "alumni";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","type","alumni_name","graduation_year","contact_phone","contact_email","title","description","donation_amount","currency","event_date","status"];
    protected $casts = ["donation_amount"=>"decimal:2","event_date"=>"date"];
}