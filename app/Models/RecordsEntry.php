<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class RecordsEntry extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "records";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","type","subject_name","reference_no","document_type","notes","issued_on","valid_until","status"];
    protected $casts = ["issued_on"=>"date","valid_until"=>"date"];
}