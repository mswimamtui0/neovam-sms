<?php
namespace App\Http\Controllers\Department;
use App\Models\MusicRecord;

class MusicController extends BaseDepartmentController {
    protected string $departmentCode = "music";
    protected string $modelClass     = MusicRecord::class;
    protected string $viewFolder     = "departments.music";

    protected array $tableColumns = ["type","title","venue","event_date","participant_count","status","recorded_by"];

    protected array $formFields = [
        "type"              => ["label"=>"Type","type"=>"select","options"=>["rehearsal","performance","class","exam","other"]],
        "title"             => ["label"=>"Title","type"=>"text","required"=>true],
        "venue"             => ["label"=>"Venue","type"=>"text"],
        "event_date"        => ["label"=>"Date","type"=>"date"],
        "event_time"        => ["label"=>"Time","type"=>"time"],
        "participant_count" => ["label"=>"Participants","type"=>"number"],
        "instruments_used"  => ["label"=>"Instruments Used","type"=>"textarea"],
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
            "instruments_used"  => "nullable|string",
            "notes"             => "nullable|string",
            "status"            => "required|in:scheduled,done,cancelled",
        ];
    }
}