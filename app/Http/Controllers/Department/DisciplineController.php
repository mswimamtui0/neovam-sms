<?php
namespace App\Http\Controllers\Department;
use App\Models\DisciplineCase;

class DisciplineController extends BaseDepartmentController {
    protected string $departmentCode = "discipline";
    protected string $modelClass     = DisciplineCase::class;
    protected string $viewFolder     = "departments.discipline";

    protected array $tableColumns = ["student_name","class_name","category","offence","status","recorded_by"];

    protected array $formFields = [
        "student_name"  => ["label" => "Student Name", "type" => "text", "required" => true],
        "class_name"    => ["label" => "Class", "type" => "text"],
        "category"      => ["label" => "Category", "type" => "select", "options" => ["minor","major","serious","praise"]],
        "offence"       => ["label" => "Offence / Reason", "type" => "text"],
        "description"   => ["label" => "Description", "type" => "textarea"],
        "action_taken"  => ["label" => "Action Taken", "type" => "text"],
        "status"        => ["label" => "Status", "type" => "select", "options" => ["open","resolved","escalated","closed"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "student_name" => "required|string|max:150",
            "class_name"   => "nullable|string|max:50",
            "category"     => "required|in:minor,major,serious,praise",
            "offence"      => "nullable|string|max:200",
            "description"  => "nullable|string",
            "action_taken" => "nullable|string|max:200",
            "status"       => "required|in:open,resolved,escalated,closed",
        ];
    }
}