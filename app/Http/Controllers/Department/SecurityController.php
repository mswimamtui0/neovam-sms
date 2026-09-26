<?php
namespace App\Http\Controllers\Department;
use App\Models\SecurityLog;

class SecurityController extends BaseDepartmentController {
    protected string $departmentCode = "security";
    protected string $modelClass     = SecurityLog::class;
    protected string $viewFolder     = "departments.security";

    protected array $tableColumns = ["type","visitor_name","purpose","status","recorded_by"];

    protected array $formFields = [
        "type"          => ["label" => "Type", "type" => "select", "options" => ["patrol","visitor","incident","gate"]],
        "visitor_name"  => ["label" => "Visitor Name", "type" => "text"],
        "visitor_phone" => ["label" => "Visitor Phone", "type" => "text"],
        "purpose"       => ["label" => "Purpose", "type" => "text"],
        "description"   => ["label" => "Description", "type" => "textarea"],
        "status"        => ["label" => "Status", "type" => "select", "options" => ["open","resolved","closed"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "type"          => "required|in:patrol,visitor,incident,gate",
            "visitor_name"  => "nullable|string|max:150",
            "visitor_phone" => "nullable|string|max:30",
            "purpose"       => "nullable|string|max:200",
            "description"   => "nullable|string",
            "status"        => "required|in:open,resolved,closed",
        ];
    }
}