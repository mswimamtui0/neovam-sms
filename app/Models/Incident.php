<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'type', 'description',
        'reported_by', 'sms_sent',
    ];

    protected $casts = [
        'sms_sent' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}