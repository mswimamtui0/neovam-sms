<?php
namespace App\Http\Controllers\Department;
use App\Models\AgricultureRecord;

class AgricultureController extends BaseDepartmentController {
    protected string $departmentCode = "agriculture";
    protected string $modelClass     = AgricultureRecord::class;
    protected string $viewFolder     = "departments.agriculture";

    protected array $tableColumns = ["type","plot","crop_animal","activity","quantity","activity_date","status","recorded_by"];

    protected array $formFields = [
        "type"          => ["label"=>"Type","type"=>"select","options"=>["crop","animal","harvest","feeding","other"]],
        "plot"          => ["label"=>"Plot / Area","type"=>"text"],
        "crop_animal"   => ["label"=>"Crop / Animal","type"=>"text","required"=>true],
        "activity"      => ["label"=>"Activity","type"=>"text"],
        "quantity"      => ["label"=>"Quantity","type"=>"number"],
        "unit"          => ["label"=>"Unit","type"=>"text"],
        "notes"         => ["label"=>"Notes","type"=>"textarea"],
        "activity_date" => ["label"=>"Date","type"=>"date"],
        "status"        => ["label"=>"Status","type"=>"select","options"=>["planned","in_progress","done","cancelled"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "type"          => "required|in:crop,animal,harvest,feeding,other",
            "plot"          => "nullable|string|max:100",
            "crop_animal"   => "required|string|max:150",
            "activity"      => "nullable|string|max:200",
            "quantity"      => "nullable|numeric|min:0",
            "unit"          => "nullable|string|max:30",
            "notes"         => "nullable|string",
            "activity_date" => "nullable|date",
            "status"        => "required|in:planned,in_progress,done,cancelled",
        ];
    }
}