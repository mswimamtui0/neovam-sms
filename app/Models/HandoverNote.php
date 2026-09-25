<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandoverNote extends Model {
 use HasFactory;
 protected $fillable = ["from_staff_id","to_staff_id","handover_date","notes","acknowledged"];
 protected $casts = ["handover_date" => "date","acknowledged" => "boolean"];
 public function fromStaff() { return $this->belongsTo(Staff::class, "from_staff_id"); }
 public function toStaff() { return $this->belongsTo(Staff::class, "to_staff_id"); }
}