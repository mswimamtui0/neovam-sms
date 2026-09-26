<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Department;
use App\Models\School;
use App\Models\Staff;
use App\Models\Subject;
use App\Services\Accounts\StaffAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    protected array $types;

    public function __construct()
    {
        $this->types = config("staff_types", []);
    }

    public function index(Request $request)
    {
        $query = Staff::query();

        if ($request->filled("staff_type")) {
            $query->where("staff_type", $request->staff_type);
        }
        if ($request->filled("department")) {
            $query->where("department", $request->department);
        }
        if ($request->filled("category")) {
            $query->where("staff_category", $request->category);
        }

        $staff = $query->latest()->paginate(20);
        $types = $this->types;

        return view("admin.staff.index", compact("staff", "types"));
    }

    public function create()
    {
        $types       = $this->types;
        $departments = Department::active()->orderBy("name")->get();
        $classrooms  = ClassRoom::orderBy("name")->get();

        return view("admin.staff.create", compact("types", "departments", "classrooms"));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "first_name"              => "required|string|max:100",
            "last_name"               => "required|string|max:100",
            "gender"                  => "required|in:male,female",
            "phone"                   => "required|string|max:30",
            "email"                   => "nullable|email",
            "staff_category"          => "required|in:teaching,non_teaching",
            "staff_type"              => "required|string",
            "role_title"              => "nullable|string|max:100",
            "primary_department_id"   => "nullable|exists:departments,id",
            "department_roles"        => "nullable|array",
            "extra_roles"             => "nullable|array",
            "subjects"                => "nullable|array",
            "subjects.*"              => "exists:subjects,id",
            "classrooms"              => "nullable|array",
            "classrooms.*"            => "exists:classrooms,id",
            "class_teacher_of"        => "nullable|exists:classrooms,id",
            "dob"                     => "nullable|date",
            "nida"                    => "nullable|string|max:50",
            "address"                 => "nullable|string|max:255",
            "employment_date"         => "nullable|date",
            "employment_type"         => "nullable|string|max:50",
            "qualification"           => "nullable|string|max:100",
            "field_of_study"          => "nullable|string|max:100",
            "institution"             => "nullable|string|max:150",
            "year_graduated"          => "nullable|integer|min:1950|max:2100",
            "emergency_contact_name"  => "nullable|string|max:150",
            "emergency_contact_phone" => "nullable|string|max:30",
            "bank_name"               => "nullable|string|max:100",
            "bank_account"            => "nullable|string|max:50",
            "tin_number"              => "nullable|string|max:50",
        ]);

        // Determine department name
        $departmentName = $this->types[$data["staff_type"]] ?? "Other";
        if (!empty($data["primary_department_id"])) {
            $dept = Department::find($data["primary_department_id"]);
            if ($dept) $departmentName = $dept->name;
        }

        $roleArray  = $data["department_roles"] ?? [];
        $extraArray = $data["extra_roles"] ?? [];
        $subjects   = $data["subjects"] ?? [];
        $classrooms = $data["classrooms"] ?? [];
        $classTeacherOf = $data["class_teacher_of"] ?? null;

        unset($data["department_roles"], $data["extra_roles"], $data["subjects"], $data["classrooms"], $data["class_teacher_of"]);

        $data["department"]           = $departmentName;
        $data["school_id"]            = School::first()?->id;
        $data["staff_no"]             = "STF-" . strtoupper(uniqid());
        $data["status"]               = "active";
        $data["department_roles"]     = empty($roleArray) ? null : implode(",", $roleArray);
        $data["extra_roles"]          = empty($extraArray) ? null : implode(",", $extraArray);
        $data["assigned_classrooms"]  = empty($classrooms) ? null : implode(",", $classrooms);

        // Auto-add "academic" to department_roles if teaching
        if ($data["staff_category"] === "teaching" && !$data["department_roles"]) {
            $data["department_roles"] = "academic";
        }

        $staff = Staff::create($data);

        // Assign subjects (teachers only)
        if ($staff->isTeacher() && !empty($subjects)) {
            $staff->subjects()->sync($subjects);
        }

        // Assign class teacher
        if ($classTeacherOf) {
            ClassRoom::where("class_teacher_id", $staff->id)->update(["class_teacher_id" => null]);
            ClassRoom::where("id", $classTeacherOf)->update(["class_teacher_id" => $staff->id]);
        }

        // Create user + send SMS
        $credentialsSent = false;
        try {
            [$user, $isNew, $plainPassword] = StaffAccountService::ensureAccount($staff);
            if ($plainPassword) {
                $credentialsSent = StaffAccountService::sendCredentialsSms($staff, $user, $plainPassword);
            }
        } catch (\Throwable $e) {
            Log::error("Staff auto-account failed", ["staff_id" => $staff->id, "error" => $e->getMessage()]);
        }

        $msg = "Staff added: " . $staff->full_name;
        $msg .= $credentialsSent ? " — credentials sent to {$staff->phone}." : " — SMS could not be sent.";

        return redirect()->route("admin.staff.index")->with("success", $msg);
    }

    public function show(Staff $staff)
    {
        $staff->load(["subjects","primaryDepartment"]);
        $classTeacherOf  = ClassRoom::where("class_teacher_id", $staff->id)->first();
        $assignedClasses = ClassRoom::whereIn("id", $staff->classroomIds())->get();

        return view("admin.staff.show", compact("staff","classTeacherOf","assignedClasses"));
    }

    public function edit(Staff $staff)
    {
        $types       = $this->types;
        $departments = Department::active()->orderBy("name")->get();
        $classrooms  = ClassRoom::orderBy("name")->get();
        $allSubjects = Subject::active()->orderBy("name")->get();
        $assigned    = $staff->subjects->pluck("id")->toArray();
        $assignedClasses = $staff->classroomIds();
        $classTeacherOf  = ClassRoom::where("class_teacher_id", $staff->id)->first();

        return view("admin.staff.edit", compact(
            "staff","types","departments","classrooms","allSubjects",
            "assigned","assignedClasses","classTeacherOf"
        ));
    }

    public function update(Request $request, Staff $staff)
    {
        $data = $request->validate([
            "first_name"              => "required|string|max:100",
            "last_name"               => "required|string|max:100",
            "gender"                  => "required|in:male,female",
            "phone"                   => "required|string|max:30",
            "email"                   => "nullable|email",
            "staff_category"          => "required|in:teaching,non_teaching",
            "staff_type"              => "required|string",
            "role_title"              => "nullable|string|max:100",
            "primary_department_id"   => "nullable|exists:departments,id",
            "department_roles"        => "nullable|array",
            "extra_roles"             => "nullable|array",
            "subjects"                => "nullable|array",
            "classrooms"              => "nullable|array",
            "class_teacher_of"        => "nullable|exists:classrooms,id",
        ]);

        $oldType       = $staff->staff_type;
        $oldCategory   = $staff->staff_category;
        $oldDepartment = $staff->department;

        $roleArray  = $data["department_roles"] ?? [];
        $extraArray = $data["extra_roles"] ?? [];
        $subjects   = $data["subjects"] ?? [];
        $classrooms = $data["classrooms"] ?? [];
        $classTeacherOf = $data["class_teacher_of"] ?? null;

        unset($data["department_roles"], $data["extra_roles"], $data["subjects"], $data["classrooms"], $data["class_teacher_of"]);

        $departmentName = $this->types[$data["staff_type"]] ?? "Other";
        if (!empty($data["primary_department_id"])) {
            $dept = Department::find($data["primary_department_id"]);
            if ($dept) $departmentName = $dept->name;
        }

        $data["department"]          = $departmentName;
        $data["department_roles"]    = empty($roleArray) ? null : implode(",", $roleArray);
        $data["extra_roles"]         = empty($extraArray) ? null : implode(",", $extraArray);
        $data["assigned_classrooms"] = empty($classrooms) ? null : implode(",", $classrooms);

        $oldSubjectsStr = $staff->subjects->pluck("name")->implode(", ");
        $oldClassesStr = $staff->assignedClasses()->pluck("name")->implode(", ");

        $staff->update($data);

        // Sync subjects
        if ($staff->isTeacher()) {
            $staff->subjects()->sync($subjects);
        } else {
            $staff->subjects()->sync([]);
        }

        // Class teacher
        ClassRoom::where("class_teacher_id", $staff->id)->update(["class_teacher_id" => null]);
        if ($classTeacherOf) {
            ClassRoom::where("id", $classTeacherOf)->update(["class_teacher_id" => $staff->id]);
        }

        // ============ SMS on ALL changes ============
        if ($staff->phone) {

            // 1. Staff type or category changed
            if ($oldType !== $staff->staff_type || $oldCategory !== $staff->staff_category) {
                SendStaffTypeChangeSms::dispatch(
                    $staff->phone,
                    $staff->full_name,
                    $oldType ?? "(none)",
                    $staff->staff_type,
                    $staff->department
                );
            }

            // 2. Department changed
            if ($oldDepartment !== $staff->department) {
                SendStaffDepartmentChangeSms::dispatch(
                    $staff->phone,
                    $staff->full_name,
                    $oldDepartment ?? "(none)",
                    $staff->department
                );
            }

            // 3. Subjects changed
            $oldSubjects = $oldSubjectsStr ?? "";
            $newSubjects = $staff->subjects->pluck("name")->implode(", ");
            if ($oldSubjects !== $newSubjects && !empty($newSubjects)) {
                SendSubjectChangeSms::dispatch(
                    $staff->phone,
                    $staff->full_name,
                    $newSubjects
                );
            }

            // 4. Classes changed
            $oldClasses = $oldClassesStr ?? "";
            $newClasses = $staff->assignedClasses()->pluck("name")->implode(", ");
            if ($oldClasses !== $newClasses && !empty($newClasses)) {
                SendClassChangeSms::dispatch(
                    $staff->phone,
                    $staff->full_name,
                    $newClasses
                );
            }

            // 5. Class teacher assigned
            if ($classTeacherOf && $classTeacherOf !== optional($classTeacherOf)->id) {
                $className = \App\Models\ClassRoom::find($classTeacherOf)?->name;
                if ($className) {
                    SendClassTeacherAssignedSms::dispatch(
                        $staff->phone,
                        $staff->full_name,
                        $className
                    );
                }
            }
        }

        return redirect()->route("admin.staff.index")->with("success", "Staff updated.");
    }

    public function destroy(Staff $staff)
    {
        try {
            if ($staff->user_id) {
                $user = \App\Models\User::find($staff->user_id);
                if ($user) {
                    $user->syncRoles([]);
                    $user->delete();
                }
            }
            $staff->subjects()->detach();
            ClassRoom::where("class_teacher_id", $staff->id)->update(["class_teacher_id" => null]);
            $staff->delete();

            return back()->with("success", "Staff deleted.");
        } catch (\Throwable $e) {
            Log::error("Staff delete failed", ["staff_id" => $staff->id, "error" => $e->getMessage()]);
            return back()->with("error", "Delete failed: " . $e->getMessage());
        }
    }

    public function resendCredentials(Staff $staff)
    {
        try {
            $result = StaffAccountService::resendCredentials($staff);
            return back()->with($result["success"] ? "success" : "error", $result["message"]);
        } catch (\Throwable $e) {
            return back()->with("error", "Failed: " . $e->getMessage());
        }
    }

    public function resetPassword(Staff $staff)
    {
        [$user, $isNew, $plainPassword] = StaffAccountService::ensureAccount($staff, true);
        return back()->with("success", "Password reset for {$staff->full_name}: {$plainPassword}");
    }

    /**
     * API: Get subjects by department (for dynamic dropdown)
     */
    public function subjectsByDepartment(Request $request)
    {
        $deptId = $request->query("department_id");
        if (!$deptId) return response()->json([]);

        $subjects = Subject::where("department_id", $deptId)
            ->where("is_active", true)
            ->orderBy("name")
            ->get(["id","name","code"]);

        return response()->json($subjects);
    }
}