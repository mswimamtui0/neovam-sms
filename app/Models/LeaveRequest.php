<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model {
    use HasFactory;
    protected $fillable = ["staff_id","leave_type","start_date","end_date","reason","status","admin_remarks"];
    protected $casts = ["start_date" => "date","end_date" => "date"];
    public function staff() { return $this->belongsTo(Staff::class); }
}