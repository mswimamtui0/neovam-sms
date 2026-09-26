<?php
namespace App\Http\Controllers\Department;
use App\Models\DramaRecord;

class DramaController extends BaseDepartmentController {
    protected string $departmentCode = "drama";
    protected string $modelClass     = DramaRecord::class;
    protected string $viewFolder     = "departments.drama";

    protected array $tableColumns = ["type","title","venue","event_date","participant_count","status","recorded_by"];

    protected array $formFields = [
        "type"              => ["label"=>"Type","type"=>"select","options"=>["rehearsal","performance","class","exam","other"]],
        "title"             => ["label"=>"Title","type"=>"text","required"=>true],
        "venue"             => ["label"=>"Venue","type"=>"text"],
        "event_date"        => ["label"=>"Date","type"=>"date"],
        "event_time"        => ["label"=>"Time","type"=>"time"],
        "participant_count" => ["label"=>"Participants","type"=>"number"],
        "props"             => ["label"=>"Props","type"=>"textarea"],
        "notes"             => ["label"=>"Notes","type"=>"textarea"],
        "status"            => ["label"=>"Status","type"=>"select","options"=>["scheduled","done","cancelled"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "type"              => "required|in:rehearsal,performance,class,exam,other",
            "title"             => "required|string|max:200",
            "venue"             => "nullable|string|max:150",
            "event_date"        => "nullable|date",
            "event_time"        => "nullable|string|max:20",
            "participant_count" => "nullable|integer|min:0",
            "props"             => "nullable|string",
            "notes"             => "nullable|string",
            "status"            => "required|in:scheduled,done,cancelled",
        ];
    }
}