<?php
namespace App\Http\Controllers\Department;
use App\Models\SpecialNeedsRecord;

class SpecialNeedsController extends BaseDepartmentController {
    protected string $departmentCode = "special_needs";
    protected string $modelClass     = SpecialNeedsRecord::class;
    protected string $viewFolder     = "departments.special_needs";

    protected array $tableColumns = ["student_name","class_name","need_type","status","recorded_by"];

    protected array $formFields = [
        "student_name"   => ["label"=>"Student","type"=>"text","required"=>true],
        "class_name"     => ["label"=>"Class","type"=>"text"],
        "need_type"      => ["label"=>"Need Type","type"=>"text"],
        "description"    => ["label"=>"Description","type"=>"textarea"],
        "support_plan"   => ["label"=>"Support Plan","type"=>"textarea"],
        "progress_notes" => ["label"=>"Progress Notes","type"=>"textarea"],
        "status"         => ["label"=>"Status","type"=>"select","options"=>["active","monitoring","closed"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "student_name"   => "required|string|max:150",
            "class_name"     => "nullable|string|max:50",
            "need_type"      => "nullable|string|max:150",
            "description"    => "nullable|string",
            "support_plan"   => "nullable|string",
            "progress_notes" => "nullable|string",
            "status"         => "required|in:active,monitoring,closed",
        ];
    }
}