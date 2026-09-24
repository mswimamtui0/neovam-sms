<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelfareNote extends Model {
    use HasFactory;
    protected $fillable = ["student_id","staff_id","type","observation","action","note_date"];
    protected $casts = ["note_date" => "date"];
    public function student() { return $this->belongsTo(Student::class); }
    public function staff()   { return $this->belongsTo(Staff::class); }
}