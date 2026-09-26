<?php
namespace App\Http\Controllers\Department;
use App\Models\HealthRecord;

class HealthController extends BaseDepartmentController {
    protected string $departmentCode = "health";
    protected string $modelClass     = HealthRecord::class;
    protected string $viewFolder     = "departments.health";

    protected array $tableColumns = ["student_name","class_name","type","severity","status","recorded_by"];

    protected array $formFields = [
        "student_name" => ["label" => "Student Name", "type" => "text", "required" => true],
        "class_name"   => ["label" => "Class", "type" => "text"],
        "type"         => ["label" => "Type", "type" => "select", "options" => ["visit","medication","emergency","injury","referral","allergy"]],
        "severity"     => ["label" => "Severity", "type" => "select", "options" => ["low","medium","high","critical"]],
        "symptoms"     => ["label" => "Symptoms", "type" => "textarea"],
        "treatment"    => ["label" => "Treatment", "type" => "textarea"],
        "notes"        => ["label" => "Notes", "type" => "textarea"],
        "status"       => ["label" => "Status", "type" => "select", "options" => ["open","treated","referred","closed"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "student_name" => "required|string|max:150",
            "class_name"   => "nullable|string|max:50",
            "type"         => "required|in:visit,medication,emergency,injury,referral,allergy",
            "severity"     => "required|in:low,medium,high,critical",
            "symptoms"     => "nullable|string",
            "treatment"    => "nullable|string",
            "notes"        => "nullable|string",
            "status"       => "required|in:open,treated,referred,closed",
        ];
    }
}