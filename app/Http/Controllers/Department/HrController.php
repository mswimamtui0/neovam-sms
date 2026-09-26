<?php
namespace App\Http\Controllers\Department;
use App\Models\HrRecord;

class HrController extends BaseDepartmentController {
    protected string $departmentCode = "hr";
    protected string $modelClass     = HrRecord::class;
    protected string $viewFolder     = "departments.hr";

    protected array $tableColumns = ["type","staff_name","subject","start_date","status","recorded_by"];

    protected array $formFields = [
        "type"        => ["label"=>"Type","type"=>"select","options"=>["leave","promotion","warning","payroll","recruitment","other"]],
        "staff_name"  => ["label"=>"Staff Name","type"=>"text","required"=>true],
        "staff_no"    => ["label"=>"Staff No","type"=>"text"],
        "subject"     => ["label"=>"Subject","type"=>"text"],
        "description" => ["label"=>"Description","type"=>"textarea"],
        "start_date"  => ["label"=>"Start Date","type"=>"date"],
        "end_date"    => ["label"=>"End Date","type"=>"date"],
        "resolution"  => ["label"=>"Resolution","type"=>"textarea"],
        "status"      => ["label"=>"Status","type"=>"select","options"=>["open","approved","rejected","closed"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "type"        => "required|in:leave,promotion,warning,payroll,recruitment,other",
            "staff_name"  => "required|string|max:150",
            "staff_no"    => "nullable|string|max:50",
            "subject"     => "nullable|string|max:200",
            "description" => "nullable|string",
            "start_date"  => "nullable|date",
            "end_date"    => "nullable|date",
            "resolution"  => "nullable|string",
            "status"      => "required|in:open,approved,rejected,closed",
        ];
    }
}