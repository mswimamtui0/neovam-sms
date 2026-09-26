<?php
namespace App\Http\Controllers\Department;
use App\Models\FeedingLog;

class FeedingController extends BaseDepartmentController {
    protected string $departmentCode = "feeding";
    protected string $modelClass     = FeedingLog::class;
    protected string $viewFolder     = "departments.feeding";

    protected array $tableColumns = ["meal_date","meal_type","menu","served_count","status","recorded_by"];

    protected array $formFields = [
        "meal_date"     => ["label" => "Date", "type" => "date", "required" => true],
        "meal_type"     => ["label" => "Meal", "type" => "select", "options" => ["breakfast","lunch","dinner","snack"]],
        "menu"          => ["label" => "Menu", "type" => "textarea"],
        "served_count"  => ["label" => "Served Count", "type" => "number"],
        "allergy_notes" => ["label" => "Allergy Notes", "type" => "textarea"],
        "stock_notes"   => ["label" => "Stock Notes", "type" => "textarea"],
        "status"        => ["label" => "Status", "type" => "select", "options" => ["planned","served","cancelled"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "meal_date"     => "required|date",
            "meal_type"     => "required|in:breakfast,lunch,dinner,snack",
            "menu"          => "nullable|string",
            "served_count"  => "nullable|integer|min:0",
            "allergy_notes" => "nullable|string",
            "stock_notes"   => "nullable|string",
            "status"        => "required|in:planned,served,cancelled",
        ];
    }
}