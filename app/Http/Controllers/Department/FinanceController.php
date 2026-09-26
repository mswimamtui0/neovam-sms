<?php
namespace App\Http\Controllers\Department;
use App\Models\FinanceRecord;

class FinanceController extends BaseDepartmentController {
    protected string $departmentCode = "finance";
    protected string $modelClass     = FinanceRecord::class;
    protected string $viewFolder     = "departments.finance";

    protected array $tableColumns = ["type","student_name","amount","currency","status","recorded_by"];

    protected array $formFields = [
        "type"         => ["label" => "Type", "type" => "select", "options" => ["invoice","payment","expense","receipt"]],
        "student_name" => ["label" => "Student", "type" => "text"],
        "amount"       => ["label" => "Amount", "type" => "number", "required" => true],
        "currency"     => ["label" => "Currency", "type" => "text"],
        "description"  => ["label" => "Description", "type" => "textarea"],
        "reference_no" => ["label" => "Reference No", "type" => "text"],
        "status"       => ["label" => "Status", "type" => "select", "options" => ["pending","paid","cancelled"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "type"         => "required|in:invoice,payment,expense,receipt",
            "student_name" => "nullable|string|max:150",
            "amount"       => "required|numeric|min:0",
            "currency"     => "nullable|string|max:10",
            "description"  => "nullable|string",
            "reference_no" => "nullable|string|max:100",
            "status"       => "required|in:pending,paid,cancelled",
        ];
    }
}