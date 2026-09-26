<?php
namespace App\Http\Controllers\Department;
use App\Models\StoreRecord;

class StoresController extends BaseDepartmentController {
    protected string $departmentCode = "stores";
    protected string $modelClass     = StoreRecord::class;
    protected string $viewFolder     = "departments.stores";

    protected array $tableColumns = ["item_name","item_code","type","quantity","unit","supplier","status","recorded_by"];

    protected array $formFields = [
        "item_name"    => ["label"=>"Item","type"=>"text","required"=>true],
        "item_code"    => ["label"=>"Item Code","type"=>"text"],
        "type"         => ["label"=>"Type","type"=>"select","options"=>["in","out","adjustment"]],
        "quantity"     => ["label"=>"Quantity","type"=>"number","required"=>true],
        "unit"         => ["label"=>"Unit","type"=>"text"],
        "issued_to"    => ["label"=>"Issued To","type"=>"text"],
        "supplier"     => ["label"=>"Supplier","type"=>"text"],
        "reference_no" => ["label"=>"Reference","type"=>"text"],
        "notes"        => ["label"=>"Notes","type"=>"textarea"],
        "status"       => ["label"=>"Status","type"=>"select","options"=>["pending","completed","cancelled"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "item_name"    => "required|string|max:150",
            "item_code"    => "nullable|string|max:100",
            "type"         => "required|in:in,out,adjustment",
            "quantity"     => "required|integer",
            "unit"         => "nullable|string|max:30",
            "issued_to"    => "nullable|string|max:150",
            "supplier"     => "nullable|string|max:150",
            "reference_no" => "nullable|string|max:100",
            "notes"        => "nullable|string",
            "status"       => "required|in:pending,completed,cancelled",
        ];
    }
}