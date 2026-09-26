<?php
namespace App\Http\Controllers\Department;
use App\Models\UniformRecord;

class UniformController extends BaseDepartmentController {
    protected string $departmentCode = "uniform";
    protected string $modelClass     = UniformRecord::class;
    protected string $viewFolder     = "departments.uniform";

    protected array $tableColumns = ["type","student_name","class_name","item","size","quantity","status","recorded_by"];

    protected array $formFields = [
        "type"         => ["label"=>"Type","type"=>"select","options"=>["order","stock","delivery","adjustment"]],
        "student_name" => ["label"=>"Student","type"=>"text"],
        "class_name"   => ["label"=>"Class","type"=>"text"],
        "item"         => ["label"=>"Item","type"=>"text","required"=>true],
        "size"         => ["label"=>"Size","type"=>"text"],
        "quantity"     => ["label"=>"Quantity","type"=>"number"],
        "amount"       => ["label"=>"Amount","type"=>"number"],
        "ready_on"     => ["label"=>"Ready On","type"=>"date"],
        "delivered_on" => ["label"=>"Delivered On","type"=>"date"],
        "notes"        => ["label"=>"Notes","type"=>"textarea"],
        "status"       => ["label"=>"Status","type"=>"select","options"=>["pending","ready","delivered","cancelled"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "type"         => "required|in:order,stock,delivery,adjustment",
            "student_name" => "nullable|string|max:150",
            "class_name"   => "nullable|string|max:50",
            "item"         => "required|string|max:150",
            "size"         => "nullable|string|max:50",
            "quantity"     => "nullable|integer|min:0",
            "amount"       => "nullable|numeric|min:0",
            "ready_on"     => "nullable|date",
            "delivered_on" => "nullable|date",
            "notes"        => "nullable|string",
            "status"       => "required|in:pending,ready,delivered,cancelled",
        ];
    }
}