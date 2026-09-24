<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisionLog extends Model {
    use HasFactory;
    protected $fillable = ["staff_id","log_date","area","status","notes"];
    protected $casts = ["log_date" => "date"];
    public function staff() { return $this->belongsTo(Staff::class); }
}