<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'phone', 'email', 'address', 'logo',
        'has_primary', 'has_secondary', 'has_alevel',
        'has_nursery', 'has_kg', 'has_pre_unit',
    ];

    protected $casts = [
        'has_primary'   => 'boolean',
        'has_secondary' => 'boolean',
        'has_alevel'    => 'boolean',
        'has_nursery'   => 'boolean',
        'has_kg'        => 'boolean',
        'has_pre_unit'  => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function classrooms()
    {
        return $this->hasMany(ClassRoom::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function enabledLevels(): array
    {
        return collect([
            'nursery'   => $this->has_nursery,
            'kg'        => $this->has_kg,
            'pre_unit'  => $this->has_pre_unit,
            'primary'   => $this->has_primary,
            'secondary' => $this->has_secondary,
            'alevel'    => $this->has_alevel,
        ])->filter()->keys()->toArray();
    }

    public function isLevelEnabled(string $level): bool
    {
        return in_array($level, $this->enabledLevels(), true);
    }
}