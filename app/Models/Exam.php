<?php
namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory, BelongsToSchool;

    protected $fillable = [
        "school_id", "classroom_id",
        "name", "term", "level",
        "start_date", "published", "published_at", "published_by",
    ];

    protected $casts = [
        "start_date"   => "date",
        "published"    => "boolean",
        "published_at" => "datetime",
    ];

    public function classroom()  { return $this->belongsTo(ClassRoom::class, "classroom_id"); }
    public function results()    { return $this->hasMany(Result::class); }
    public function publisher()  { return $this->belongsTo(User::class, "published_by"); }

    public function scopePublished($q)   { return $q->where("published", true); }
    public function scopePending($q)     { return $q->where("published", false); }
}