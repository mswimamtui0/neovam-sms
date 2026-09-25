<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model {
 use HasFactory, BelongsToSchool;

 protected $fillable = [
 "school_id","year","label","start_date","end_date",
 "is_current","is_closed","closed_at","closed_by",
 ];

 protected $casts = [
 "start_date" => "date",
 "end_date" => "date",
 "is_current" => "boolean",
 "is_closed" => "boolean",
 "closed_at" => "datetime",
 ];

 public function closer() { return $this->belongsTo(User::class, "closed_by"); }

 public static function current(): ?self {
 return self::where("is_current", true)->first();
 }
}