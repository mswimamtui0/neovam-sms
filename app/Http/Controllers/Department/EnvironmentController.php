<?php
namespace App\Http\Controllers\Department;
use App\Models\EnvironmentLog;

class EnvironmentController extends BaseDepartmentController {
    protected string $departmentCode = "environment";
    protected string $modelClass     = EnvironmentLog::class;
    protected string $viewFolder     = "departments.environment";

    protected array $tableColumns = ["area","type","rating","status","recorded_by"];

    protected array $formFields = [
        "area"        => ["label" => "Area", "type" => "text", "required" => true],
        "type"        => ["label" => "Type", "type" => "select", "options" => ["cleaning","inspection","waste","maintenance"]],
        "description" => ["label" => "Description", "type" => "textarea"],
        "rating"      => ["label" => "Rating (1-5)", "type" => "number"],
        "status"      => ["label" => "Status", "type" => "select", "options" => ["open","resolved","closed"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "area"        => "required|string|max:100",
            "type"        => "required|in:cleaning,inspection,waste,maintenance",
            "description" => "nullable|string",
            "rating"      => "nullable|integer|min:1|max:5",
            "status"      => "required|in:open,resolved,closed",
        ];
    }
}