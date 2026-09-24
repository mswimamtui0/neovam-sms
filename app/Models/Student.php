<?php

namespace App\Models;

use App\Models\Scopes\ParentStudentScope;
use App\Models\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Student extends Model
{
    use HasFactory, BelongsToSchool, LogsActivity;

    protected $fillable = [
        "school_id", "classroom_id", "user_id",
        "admission_no", "first_name", "last_name", "gender", "dob", "level",
        "parent_name", "parent_phone", "parent_email",
        "status",
    ];

    protected $casts = [
        "dob" => "date",
    ];

    protected $appends = ["full_name"];

    protected static function booted(): void
    {
        static::addGlobalScope(new ParentStudentScope());
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(["first_name","last_name","level","parent_phone"])
            ->logOnlyDirty()
            ->useLogName("student");
    }

    public function school()       { return $this->belongsTo(School::class); }
    public function classroom()    { return $this->belongsTo(ClassRoom::class); }
    public function user()         { return $this->belongsTo(User::class); }
    public function attendances()  { return $this->hasMany(Attendance::class); }
    public function results()      { return $this->hasMany(Result::class); }
    public function incidents()    { return $this->hasMany(Incident::class); }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}