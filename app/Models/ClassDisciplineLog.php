<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassDisciplineLog extends Model {
    use HasFactory;
    protected $fillable = ["student_id","staff_id","type","reason","action_taken","log_date"];
    protected $casts = ["log_date" => "date"];
    public function student() { return $this->belongsTo(Student::class); }
    public function staff()   { return $this->belongsTo(Staff::class); }
}