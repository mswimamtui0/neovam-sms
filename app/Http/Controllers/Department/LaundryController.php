<?php
namespace App\Http\Controllers\Department;
use App\Models\LaundryRecord;

class LaundryController extends BaseDepartmentController {
    protected string $departmentCode = "laundry";
    protected string $modelClass     = LaundryRecord::class;
    protected string $viewFolder     = "departments.laundry";

    protected array $tableColumns = ["type","dorm_name","item","quantity","received_on","status","recorded_by"];

    protected array $formFields = [
        "type"        => ["label"=>"Type","type"=>"select","options"=>["linen","uniform","curtain","other"]],
        "dorm_name"   => ["label"=>"Dorm","type"=>"text"],
        "item"        => ["label"=>"Item","type"=>"text","required"=>true],
        "quantity"    => ["label"=>"Quantity","type"=>"number"],
        "received_on" => ["label"=>"Received On","type"=>"date"],
        "returned_on" => ["label"=>"Returned On","type"=>"date"],
        "notes"       => ["label"=>"Notes","type"=>"textarea"],
        "status"      => ["label"=>"Status","type"=>"select","options"=>["pending","in_progress","returned","lost"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "type"        => "required|in:linen,uniform,curtain,other",
            "dorm_name"   => "nullable|string|max:100",
            "item"        => "required|string|max:150",
            "quantity"    => "nullable|integer|min:0",
            "received_on" => "nullable|date",
            "returned_on" => "nullable|date",
            "notes"       => "nullable|string",
            "status"      => "required|in:pending,in_progress,returned,lost",
        ];
    }
}