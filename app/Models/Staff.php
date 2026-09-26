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
        "staff_type", "staff_category", "primary_department_id",
        "dob", "nida", "photo", "address",
        "employment_date", "employment_type",
        "qualification", "field_of_study", "institution", "year_graduated",
        "emergency_contact_name", "emergency_contact_phone",
        "bank_name", "bank_account", "tin_number",
        "assigned_classrooms", "department_roles", "extra_roles",
    ];

    protected $casts = [
        "dob"             => "date",
        "employment_date" => "date",
    ];

    protected $appends = ["full_name"];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(["first_name","last_name","staff_type","staff_category","department","role_title"])
            ->logOnlyDirty()
            ->useLogName("staff");
    }

    /* ============ Relationships ============ */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, "staff_subjects")->withTimestamps();
    }

    public function primaryDepartment()
    {
        return $this->belongsTo(Department::class, "primary_department_id");
    }

    /* ============ Accessors ============ */

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isTeacher(): bool
    {
        return $this->staff_category === "teaching"
            || in_array($this->staff_type, [
                "Teacher","Head of Department","Academic Master","Lab Technician"
            ], true);
    }

    public function isTeaching(): bool
    {
        return $this->staff_category === "teaching";
    }

    public function isNonTeaching(): bool
    {
        return $this->staff_category === "non_teaching";
    }

    /**
     * Route name of the staff's home dashboard (their primary department).
     */
    public function landingRoute(): ?string
    {
        $code = optional($this->primaryDepartment)->code;

        if (!$code) {
            // fallback to first role ticked
            $roles = $this->allRoles();
            foreach ($roles as $r) {
                if ($r && $r !== "academic" && $r !== "class_teacher") {
                    $code = $r;
                    break;
                }
            }
        }

        if (!$code) {
            return null;
        }

        $route = "dept.{$code}.index";
        return \Illuminate\Support\Facades\Route::has($route) ? $route : null;
    }

    /**
     * All department codes this staff belongs to (primary + ticks).
     */
    public function departmentCodes(): array
    {
        $codes = [];
        if ($this->primaryDepartment) $codes[] = $this->primaryDepartment->code;

        foreach (array_merge($this->roleList(), $this->extraRoleList()) as $r) {
            if ($r && $r !== "academic") $codes[] = $r;
        }

        return array_values(array_unique(array_filter($codes)));
    }

    /* ============ Classrooms ============ */

    public function classroomIds(): array
    {
        if (!$this->assigned_classrooms) return [];
        return array_filter(explode(",", $this->assigned_classrooms));
    }

    public function assignedClasses()
    {
        return ClassRoom::whereIn("id", $this->classroomIds())->get();
    }

    /* ============ Department Roles ============ */

    public function roleList(): array
    {
        if (!$this->department_roles) return [];
        return array_values(array_filter(array_map("trim", explode(",", $this->department_roles))));
    }

    public function extraRoleList(): array
    {
        if (!$this->extra_roles) return [];
        return array_values(array_filter(array_map("trim", explode(",", $this->extra_roles))));
    }

    public function allRoles(): array
    {
        return array_unique(array_merge($this->roleList(), $this->extraRoleList()));
    }

    public function hasDepartmentRole(string $role): bool
    {
        return in_array($role, $this->allRoles(), true);
    }

    public function hasAnyDepartmentRole(): bool
    {
        return count($this->allRoles()) > 0;
    }

    /* ============ Options ============ */

    public static function departmentRoleOptions(): array
    {
        return [
            "academic"       => "Academic (Teaching)",
            "class_teacher"  => "Class Teacher",
            "duty"           => "Teacher on Duty",
            "discipline"     => "Discipline",
            "health"         => "Health",
            "sports"         => "Sports",
            "boarding"       => "Boarding",
            "feeding"        => "Feeding",
            "library"        => "Library",
            "guidance"       => "Guidance & Counseling",
            "environment"    => "Environment",
            "security"       => "Security",
            "finance"        => "Finance / Bursar",
            "administration" => "Administration",
        ];
    }

    public static function teachingTypes(): array
    {
        return ["Teacher","Head of Department","Academic Master","Lab Technician"];
    }

    public static function nonTeachingTypes(): array
    {
        return [
            "Bursar","Accounts Clerk","Head of School","Deputy Head",
            "Secretary","Librarian","ICT Officer","Nurse",
            "Cleaner","Security Guard","Cook",
        ];
    }
}