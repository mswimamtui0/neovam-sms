<?php
namespace App\Http\Controllers\Department;
use App\Models\CounselingSession;

class GuidanceController extends BaseDepartmentController {
    protected string $departmentCode = "guidance";
    protected string $modelClass     = CounselingSession::class;
    protected string $viewFolder     = "departments.guidance";

    protected array $tableColumns = ["student_name","session_type","status","recorded_by"];

    protected array $formFields = [
        "student_name" => ["label" => "Student", "type" => "text", "required" => true],
        "session_type" => ["label" => "Session Type", "type" => "select", "options" => ["general","academic","career","personal"]],
        "summary"      => ["label" => "Summary", "type" => "textarea"],
        "follow_up"    => ["label" => "Follow Up", "type" => "textarea"],
        "status"       => ["label" => "Status", "type" => "select", "options" => ["open","closed"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "student_name" => "required|string|max:150",
            "session_type" => "required|in:general,academic,career,personal",
            "summary"      => "nullable|string",
            "follow_up"    => "nullable|string",
            "status"       => "required|in:open,closed",
        ];
    }
}