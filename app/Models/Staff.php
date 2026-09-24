<?php

namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Staff extends Model
{
    use HasFactory, BelongsToSchool, LogsActivity;

    protected $table = "staff";

    protected $fillable = [
        "school_id", "user_id",
        "staff_no", "first_name", "last_name", "gender",
        "phone", "email", "department", "role_title", "status",
        "staff_type", "dob", "nida", "photo", "address",
        "employment_date", "employment_type",
        "qualification", "field_of_study", "institution", "year_graduated",
        "emergency_contact_name", "emergency_contact_phone",
        "bank_name", "bank_account", "tin_number",
        "assigned_classrooms",
    ];

    protected $casts = [
        "dob"             => "date",
        "employment_date" => "date",
    ];

    protected $appends = ["full_name"];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(["first_name","last_name","staff_type","department","role_title"])
            ->logOnlyDirty()
            ->useLogName("staff");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, "staff_subjects")->withTimestamps();
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isTeacher(): bool
    {
        return in_array($this->staff_type, [
            "Teacher","Head of Department","Academic Master","Lab Technician"
        ], true);
    }

    /**
     * Returns the classroom IDs this teacher is assigned to.
     */
    public function classroomIds(): array
    {
        if (!$this->assigned_classrooms) return [];
        return array_filter(explode(",", $this->assigned_classrooms));
    }

    /**
     * Classes assigned to this teacher.
     */
    public function assignedClasses()
    {
        return ClassRoom::whereIn("id", $this->classroomIds())->get();
    }
}