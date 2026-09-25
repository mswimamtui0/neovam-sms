<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backup extends Model {
    use HasFactory;

    protected $fillable = [
        "school_id","filename","disk","path","size","type","scope",
        "status","notes","meta","created_by",
    ];

    protected $casts = [
        "meta" => "array",
        "size" => "integer",
    ];

    public function school()  { return $this->belongsTo(School::class); }
    public function creator() { return $this->belongsTo(User::class, "created_by"); }

    public function humanSize(): string
    {
        $bytes = $this->size;
        $units = ["B","KB","MB","GB","TB"];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . " " . $units[$i];
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            "completed" => "green",
            "failed"    => "red",
            "pending"   => "yellow",
            default     => "gray",
        };
    }
}