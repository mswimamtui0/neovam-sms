<?php
namespace App\Http\Controllers\Department;
use App\Models\MaintenanceRecord;

class MaintenanceController extends BaseDepartmentController {
    protected string $departmentCode = "maintenance";
    protected string $modelClass     = MaintenanceRecord::class;
    protected string $viewFolder     = "departments.maintenance";

    protected array $tableColumns = ["location","item","issue","priority","assigned_to","status","recorded_by"];

    protected array $formFields = [
        "location"     => ["label"=>"Location","type"=>"text","required"=>true],
        "item"         => ["label"=>"Item","type"=>"text"],
        "issue"        => ["label"=>"Issue","type"=>"text","required"=>true],
        "description"  => ["label"=>"Description","type"=>"textarea"],
        "assigned_to"  => ["label"=>"Assigned To","type"=>"text"],
        "priority"     => ["label"=>"Priority","type"=>"select","options"=>["low","normal","high","urgent"]],
        "completed_on" => ["label"=>"Completed On","type"=>"date"],
        "parts_used"   => ["label"=>"Parts Used","type"=>"textarea"],
        "cost"         => ["label"=>"Cost","type"=>"number"],
        "status"       => ["label"=>"Status","type"=>"select","options"=>["open","in_progress","completed","cancelled"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "location"     => "required|string|max:150",
            "item"         => "nullable|string|max:150",
            "issue"        => "required|string|max:200",
            "description"  => "nullable|string",
            "assigned_to"  => "nullable|string|max:150",
            "priority"     => "required|in:low,normal,high,urgent",
            "completed_on" => "nullable|date",
            "parts_used"   => "nullable|string",
            "cost"         => "nullable|numeric|min:0",
            "status"       => "required|in:open,in_progress,completed,cancelled",
        ];
    }
}