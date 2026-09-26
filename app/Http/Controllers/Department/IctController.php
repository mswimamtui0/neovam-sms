<?php
namespace App\Http\Controllers\Department;
use App\Models\IctRecord;

class IctController extends BaseDepartmentController {
    protected string $departmentCode = "ict";
    protected string $modelClass     = IctRecord::class;
    protected string $viewFolder     = "departments.ict";

    protected array $tableColumns = ["type","title","device","priority","assigned_to","status","recorded_by"];

    protected array $formFields = [
        "type"        => ["label"=>"Type","type"=>"select","options"=>["ticket","device","backup","network","other"]],
        "title"       => ["label"=>"Title","type"=>"text","required"=>true],
        "description" => ["label"=>"Description","type"=>"textarea"],
        "reported_by" => ["label"=>"Reported By","type"=>"text"],
        "assigned_to" => ["label"=>"Assigned To","type"=>"text"],
        "device"      => ["label"=>"Device","type"=>"text"],
        "priority"    => ["label"=>"Priority","type"=>"select","options"=>["low","normal","high","urgent"]],
        "resolution"  => ["label"=>"Resolution","type"=>"textarea"],
        "status"      => ["label"=>"Status","type"=>"select","options"=>["open","in_progress","resolved","closed"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "type"        => "required|in:ticket,device,backup,network,other",
            "title"       => "required|string|max:200",
            "description" => "nullable|string",
            "reported_by" => "nullable|string|max:150",
            "assigned_to" => "nullable|string|max:150",
            "device"      => "nullable|string|max:150",
            "priority"    => "required|in:low,normal,high,urgent",
            "resolution"  => "nullable|string",
            "status"      => "required|in:open,in_progress,resolved,closed",
        ];
    }
}