<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use App\Models\Traits\DepartmentRecord;
use Illuminate\Database\Eloquent\Model;

class FeedingLog extends Model {
    use BelongsToSchool, DepartmentRecord;
    public $departmentCode = "feeding";
    protected $fillable = ["school_id","department_id","staff_id","recorded_by","meal_date","meal_type","menu","served_count","allergy_notes","stock_notes","status"];
    protected $casts = ["meal_date" => "date"];
}