<?php
namespace App\Http\Controllers\Department;
use App\Models\RecordsEntry;

class RecordsController extends BaseDepartmentController {
    protected string $departmentCode = "records";
    protected string $modelClass     = RecordsEntry::class;
    protected string $viewFolder     = "departments.records";

    protected array $tableColumns = ["type","subject_name","document_type","reference_no","issued_on","status","recorded_by"];

    protected array $formFields = [
        "type"          => ["label"=>"Type","type"=>"select","options"=>["student","staff","academic","certificate","other"]],
        "subject_name"  => ["label"=>"Subject Name","type"=>"text","required"=>true],
        "reference_no"  => ["label"=>"Reference No","type"=>"text"],
        "document_type" => ["label"=>"Document Type","type"=>"text"],
        "notes"         => ["label"=>"Notes","type"=>"textarea"],
        "issued_on"     => ["label"=>"Issued On","type"=>"date"],
        "valid_until"   => ["label"=>"Valid Until","type"=>"date"],
        "status"        => ["label"=>"Status","type"=>"select","options"=>["active","archived","lost","replaced"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "type"          => "required|in:student,staff,academic,certificate,other",
            "subject_name"  => "required|string|max:150",
            "reference_no"  => "nullable|string|max:100",
            "document_type" => "nullable|string|max:150",
            "notes"         => "nullable|string",
            "issued_on"     => "nullable|date",
            "valid_until"   => "nullable|date",
            "status"        => "required|in:active,archived,lost,replaced",
        ];
    }
}