<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearlyDutyCalendar extends Model {
 use HasFactory;
 protected $fillable = ["school_id","year","title","notes","status","created_by"];

 public function assignments() { return $this->hasMany(YearlyDutyAssignment::class, "calendar_id"); }
 public function creator() { return $this->belongsTo(User::class, "created_by"); }

 public function scopeActive($q) { return $q->where("status", "active"); }
}