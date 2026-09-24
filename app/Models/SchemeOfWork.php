<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeOfWork extends Model {
    use HasFactory;
    protected $table = "schemes_of_work";
    protected $fillable = ["staff_id","classroom_id","subject_id","term","year","content","status"];
    public function staff()     { return $this->belongsTo(Staff::class); }
    public function classroom() { return $this->belongsTo(ClassRoom::class); }
    public function subject()   { return $this->belongsTo(Subject::class); }
}