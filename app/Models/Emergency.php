<?php

namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    use HasFactory, BelongsToSchool;

    protected $fillable = [
        "school_id","department_id","staff_id","recorded_by",
        "student_id","student_name","class_name",
        "type","severity","description","action_taken","location",
        "head_notified","parent_notified","health_teacher_notified",
        "head_notified_at","parent_notified_at","health_teacher_notified_at",
        "parent_response","follow_up","resolved_at","status",
    ];

    protected $casts = [
        "head_notified"           => "boolean",
        "parent_notified"         => "boolean",
        "health_teacher_notified" => "boolean",
        "head_notified_at"        => "datetime",
        "parent_notified_at"      => "datetime",
        "health_teacher_notified_at" => "datetime",
        "resolved_at"             => "datetime",
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function isCritical(): bool
    {
        return $this->severity === "critical";
    }

    public function isOpen(): bool
    {
        return in_array($this->status, ["open","monitoring"], true);
    }

    public static function severityOptions(): array
    {
        return [
            "critical" => "Critical — immediate attention",
            "urgent"   => "Urgent — attend soon",
            "normal"   => "Normal — routine attention",
            "info"     => "Info — routine notice",
        ];
    }

    public static function typeOptions(): array
    {
        return [
            "illness"   => "Illness",
            "injury"    => "Injury",
            "emotional" => "Emotional",
            "other"     => "Other",
        ];
    }
}