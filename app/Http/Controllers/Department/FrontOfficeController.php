<?php
namespace App\Http\Controllers\Department;
use App\Models\FrontOfficeRecord;

class FrontOfficeController extends BaseDepartmentController {
    protected string $departmentCode = "front_office";
    protected string $modelClass     = FrontOfficeRecord::class;
    protected string $viewFolder     = "departments.front_office";

    protected array $tableColumns = ["type","visitor_name","host_name","purpose","visit_date","status","recorded_by"];

    protected array $formFields = [
        "type"          => ["label"=>"Type","type"=>"select","options"=>["visitor","call","message","appointment"]],
        "visitor_name"  => ["label"=>"Visitor Name","type"=>"text"],
        "visitor_phone" => ["label"=>"Visitor Phone","type"=>"text"],
        "host_name"     => ["label"=>"Host / Staff","type"=>"text"],
        "purpose"       => ["label"=>"Purpose","type"=>"text"],
        "message"       => ["label"=>"Message","type"=>"textarea"],
        "visit_date"    => ["label"=>"Date","type"=>"date"],
        "visit_time"    => ["label"=>"Time","type"=>"time"],
        "status"        => ["label"=>"Status","type"=>"select","options"=>["open","resolved","closed"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "type"          => "required|in:visitor,call,message,appointment",
            "visitor_name"  => "nullable|string|max:150",
            "visitor_phone" => "nullable|string|max:30",
            "host_name"     => "nullable|string|max:150",
            "purpose"       => "nullable|string|max:200",
            "message"       => "nullable|string",
            "visit_date"    => "nullable|date",
            "visit_time"    => "nullable|string|max:20",
            "status"        => "required|in:open,resolved,closed",
        ];
    }
}