<?php

namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory, BelongsToSchool;

    protected $fillable = [
        "school_id", "department_id", "name", "code", "levels", "category", "is_active",
    ];

    protected $casts = [
        "is_active" => "boolean",
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function staff()
    {
        return $this->belongsToMany(Staff::class, "staff_subjects")->withTimestamps();
    }

    public function levelList(): array
    {
        return array_filter(explode(",", $this->levels ?? ""));
    }
}