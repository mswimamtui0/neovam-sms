<?php
namespace App\Http\Controllers\Department;
use App\Models\AdministrationLog;

class AdministrationController extends BaseDepartmentController {
    protected string $departmentCode = "administration";
    protected string $modelClass     = AdministrationLog::class;
    protected string $viewFolder     = "departments.administration";

    protected array $tableColumns = ["type","title","audience","status","recorded_by"];

    protected array $formFields = [
        "type"     => ["label" => "Type", "type" => "select", "options" => ["letter","meeting","circular","notice","report"]],
        "title"    => ["label" => "Title", "type" => "text", "required" => true],
        "body"     => ["label" => "Body", "type" => "textarea"],
        "audience" => ["label" => "Audience", "type" => "select", "options" => ["staff","parents","all","class"]],
        "status"   => ["label" => "Status", "type" => "select", "options" => ["draft","published","archived"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "type"     => "required|in:letter,meeting,circular,notice,report",
            "title"    => "required|string|max:200",
            "body"     => "nullable|string",
            "audience" => "nullable|in:staff,parents,all,class",
            "status"   => "required|in:draft,published,archived",
        ];
    }
}