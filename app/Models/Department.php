<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        "school_id", "name", "code", "type", "color", "description", "is_active",
    ];

    protected $casts = [
        "is_active" => "boolean",
    ];

    /* ============ Relationships ============ */

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class, "department", "name");
    }

    /* ============ Scopes ============ */

    public function scopeTeaching($q)
    {
        return $q->where("type", "teaching");
    }

    public function scopeNonTeaching($q)
    {
        return $q->where("type", "non_teaching");
    }

    public function scopeActive($q)
    {
        return $q->where("is_active", true);
    }

    /* ============ Static helpers (RENAMED to avoid recursion) ============ */

    public static function teachingOptions(): array
    {
        return self::query()->teaching()->active()->orderBy("name")->pluck("name", "id")->toArray();
    }

    public static function nonTeachingOptions(): array
    {
        return self::query()->nonTeaching()->active()->orderBy("name")->pluck("name", "id")->toArray();
    }
}