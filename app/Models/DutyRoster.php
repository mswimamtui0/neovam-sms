<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DutyRoster extends Model {
 use HasFactory;
 protected $fillable = ["staff_id","duty_date","duty_type","shift","location","notes"];
 protected $casts = ["duty_date" => "date"];
 public function staff() { return $this->belongsTo(Staff::class); }
}