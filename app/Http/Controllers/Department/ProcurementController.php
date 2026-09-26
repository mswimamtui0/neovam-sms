<?php
namespace App\Http\Controllers\Department;
use App\Models\ProcurementRecord;

class ProcurementController extends BaseDepartmentController {
    protected string $departmentCode = "procurement";
    protected string $modelClass     = ProcurementRecord::class;
    protected string $viewFolder     = "departments.procurement";

    protected array $tableColumns = ["type","item_name","supplier","quantity","total_amount","status","recorded_by"];

    protected array $formFields = [
        "type"         => ["label"=>"Type","type"=>"select","options"=>["order","request","quotation","delivery"]],
        "item_name"    => ["label"=>"Item","type"=>"text","required"=>true],
        "supplier"     => ["label"=>"Supplier","type"=>"text"],
        "quantity"     => ["label"=>"Quantity","type"=>"number"],
        "unit"         => ["label"=>"Unit","type"=>"text"],
        "unit_price"   => ["label"=>"Unit Price","type"=>"number"],
        "total_amount" => ["label"=>"Total Amount","type"=>"number"],
        "currency"     => ["label"=>"Currency","type"=>"text"],
        "expected_on"  => ["label"=>"Expected On","type"=>"date"],
        "received_on"  => ["label"=>"Received On","type"=>"date"],
        "notes"        => ["label"=>"Notes","type"=>"textarea"],
        "status"       => ["label"=>"Status","type"=>"select","options"=>["pending","approved","ordered","received","cancelled"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "type"         => "required|in:order,request,quotation,delivery",
            "item_name"    => "required|string|max:150",
            "supplier"     => "nullable|string|max:150",
            "quantity"     => "nullable|integer|min:0",
            "unit"         => "nullable|string|max:30",
            "unit_price"   => "nullable|numeric|min:0",
            "total_amount" => "nullable|numeric|min:0",
            "currency"     => "nullable|string|max:10",
            "expected_on"  => "nullable|date",
            "received_on"  => "nullable|date",
            "notes"        => "nullable|string",
            "status"       => "required|in:pending,approved,ordered,received,cancelled",
        ];
    }
}