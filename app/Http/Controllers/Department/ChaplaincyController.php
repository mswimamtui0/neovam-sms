<?php
namespace App\Http\Controllers\Department;
use App\Models\ChaplaincyRecord;

class ChaplaincyController extends BaseDepartmentController {
    protected string $departmentCode = "chaplaincy";
    protected string $modelClass     = ChaplaincyRecord::class;
    protected string $viewFolder     = "departments.chaplaincy";

    protected array $tableColumns = ["type","title","venue","event_date","attendees","status","recorded_by"];

    protected array $formFields = [
        "type"       => ["label"=>"Type","type"=>"select","options"=>["service","prayer","visit","counselling","other"]],
        "title"      => ["label"=>"Title","type"=>"text","required"=>true],
        "venue"      => ["label"=>"Venue","type"=>"text"],
        "event_date" => ["label"=>"Date","type"=>"date"],
        "event_time" => ["label"=>"Time","type"=>"time"],
        "attendees"  => ["label"=>"Attendees","type"=>"number"],
        "notes"      => ["label"=>"Notes","type"=>"textarea"],
        "status"     => ["label"=>"Status","type"=>"select","options"=>["scheduled","done","cancelled"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "type"       => "required|in:service,prayer,visit,counselling,other",
            "title"      => "required|string|max:200",
            "venue"      => "nullable|string|max:150",
            "event_date" => "nullable|date",
            "event_time" => "nullable|string|max:20",
            "attendees"  => "nullable|integer|min:0",
            "notes"      => "nullable|string",
            "status"     => "required|in:scheduled,done,cancelled",
        ];
    }
}