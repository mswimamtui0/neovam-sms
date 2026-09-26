<?php
namespace App\Http\Controllers\Department;
use App\Models\BoardingLog;

class BoardingController extends BaseDepartmentController {
    protected string $departmentCode = "boarding";
    protected string $modelClass     = BoardingLog::class;
    protected string $viewFolder     = "departments.boarding";

    protected array $tableColumns = ["dorm_name","type","description","status","recorded_by"];

    protected array $formFields = [
        "dorm_name"   => ["label" => "Dorm", "type" => "text", "required" => true],
        "type"        => ["label" => "Type", "type" => "select", "options" => ["roll_call","incident","inspection","maintenance"]],
        "description" => ["label" => "Description", "type" => "textarea"],
        "status"      => ["label" => "Status", "type" => "select", "options" => ["open","resolved","closed"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "dorm_name"   => "required|string|max:100",
            "type"        => "required|in:roll_call,incident,inspection,maintenance",
            "description" => "nullable|string",
            "status"      => "required|in:open,resolved,closed",
        ];
    }
}