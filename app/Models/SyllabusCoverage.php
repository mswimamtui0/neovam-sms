<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusCoverage extends Model {
    use HasFactory;
    protected $table = "syllabus_coverage";
    protected $fillable = ["classroom_id","subject_id","staff_id","term","year","planned_topics","covered_topics","notes"];
    public function classroom() { return $this->belongsTo(ClassRoom::class); }
    public function subject()   { return $this->belongsTo(Subject::class); }
    public function staff()     { return $this->belongsTo(Staff::class); }
    public function percent(): int {
        if ($this->planned_topics === 0) return 0;
        return (int) round(($this->covered_topics / $this->planned_topics) * 100);
    }
}