<?php
namespace App\Http\Controllers\Department;
use App\Models\PsychosocialRecord;

class PsychosocialController extends BaseDepartmentController {
    protected string $departmentCode = "counselling";
    protected string $modelClass     = PsychosocialRecord::class;
    protected string $viewFolder     = "departments.counselling";

    protected array $tableColumns = ["student_name","class_name","session_type","status","recorded_by"];

    protected array $formFields = [
        "student_name" => ["label"=>"Student","type"=>"text","required"=>true],
        "class_name"   => ["label"=>"Class","type"=>"text"],
        "session_type" => ["label"=>"Session Type","type"=>"select","options"=>["personal","academic","career","family","other"]],
        "summary"      => ["label"=>"Summary","type"=>"textarea"],
        "follow_up"    => ["label"=>"Follow Up","type"=>"textarea"],
        "referral"     => ["label"=>"Referral","type"=>"textarea"],
        "status"       => ["label"=>"Status","type"=>"select","options"=>["open","monitoring","closed"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "student_name" => "required|string|max:150",
            "class_name"   => "nullable|string|max:50",
            "session_type" => "required|in:personal,academic,career,family,other",
            "summary"      => "nullable|string",
            "follow_up"    => "nullable|string",
            "referral"     => "nullable|string",
            "status"       => "required|in:open,monitoring,closed",
        ];
    }
}