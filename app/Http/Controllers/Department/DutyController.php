<?php
namespace App\Http\Controllers\Department;
use App\Models\DutyLog;

class DutyController extends BaseDepartmentController {
    protected string $departmentCode = "duty";
    protected string $modelClass     = DutyLog::class;
    protected string $viewFolder     = "departments.duty";

    protected array $tableColumns = ["duty_date","shift","duty_type","status","recorded_by"];

    protected array $formFields = [
        "duty_date"      => ["label" => "Duty Date", "type" => "date", "required" => true],
        "shift"          => ["label" => "Shift", "type" => "select", "options" => ["morning","day","evening","night"]],
        "duty_type"      => ["label" => "Duty Type", "type" => "text"],
        "handover_notes" => ["label" => "Handover Notes", "type" => "textarea"],
        "incidents"      => ["label" => "Incidents", "type" => "textarea"],
        "status"         => ["label" => "Status", "type" => "select", "options" => ["scheduled","done","missed"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "duty_date"      => "required|date",
            "shift"          => "required|in:morning,day,evening,night",
            "duty_type"      => "nullable|string|max:100",
            "handover_notes" => "nullable|string",
            "incidents"      => "nullable|string",
            "status"         => "required|in:scheduled,done,missed",
        ];
    }
}