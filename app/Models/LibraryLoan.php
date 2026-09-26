<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class LibraryLoan extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "library";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","book_title","book_code","student_id","student_name","borrowed_on","due_on","returned_on","status","parent_notified"];
    protected $casts = ["borrowed_on"=>"date","due_on"=>"date","returned_on"=>"date","parent_notified"=>"boolean"];
}