<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model {
 use HasFactory;
 protected $fillable = ["school_id","staff_id","title","body","audience","is_pinned","publish_date","expiry_date"];
 protected $casts = ["is_pinned" => "boolean","publish_date" => "date","expiry_date" => "date"];
 public function staff() { return $this->belongsTo(Staff::class); }
}