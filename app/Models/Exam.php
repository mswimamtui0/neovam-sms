<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id', 'classroom_id',
        'name', 'term', 'level',
        'start_date', 'published',
    ];

    protected $casts = [
        'start_date' => 'date',
        'published'  => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classroom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}