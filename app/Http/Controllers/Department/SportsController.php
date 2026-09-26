<?php
namespace App\Http\Controllers\Department;
use App\Models\SportsRecord;

class SportsController extends BaseDepartmentController {
    protected string $departmentCode = "sports";
    protected string $modelClass     = SportsRecord::class;
    protected string $viewFolder     = "departments.sports";

    protected array $tableColumns = ["sport_name","team_name","event_type","opponent","result","recorded_by"];

    protected array $formFields = [
        "sport_name" => ["label" => "Sport", "type" => "text", "required" => true],
        "team_name"  => ["label" => "Team", "type" => "text"],
        "event_type" => ["label" => "Event Type", "type" => "select", "options" => ["training","match","tournament","injury"]],
        "opponent"   => ["label" => "Opponent", "type" => "text"],
        "venue"      => ["label" => "Venue", "type" => "text"],
        "result"     => ["label" => "Result", "type" => "text"],
        "notes"      => ["label" => "Notes", "type" => "textarea"],
    ];

    protected function rules(?int $id = null): array {
        return [
            "sport_name" => "required|string|max:100",
            "team_name"  => "nullable|string|max:100",
            "event_type" => "required|in:training,match,tournament,injury",
            "opponent"   => "nullable|string|max:100",
            "venue"      => "nullable|string|max:100",
            "result"     => "nullable|string|max:100",
            "notes"      => "nullable|string",
        ];
    }
}