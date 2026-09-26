<?php
namespace App\Http\Controllers\Department;
use App\Models\AlumniRecord;

class AlumniController extends BaseDepartmentController {
    protected string $departmentCode = "alumni";
    protected string $modelClass     = AlumniRecord::class;
    protected string $viewFolder     = "departments.alumni";

    protected array $tableColumns = ["type","alumni_name","graduation_year","title","event_date","status","recorded_by"];

    protected array $formFields = [
        "type"            => ["label"=>"Type","type"=>"select","options"=>["event","donation","reunion","contact","other"]],
        "alumni_name"     => ["label"=>"Alumni Name","type"=>"text"],
        "graduation_year" => ["label"=>"Graduation Year","type"=>"text"],
        "contact_phone"   => ["label"=>"Phone","type"=>"text"],
        "contact_email"   => ["label"=>"Email","type"=>"text"],
        "title"           => ["label"=>"Title","type"=>"text","required"=>true],
        "description"     => ["label"=>"Description","type"=>"textarea"],
        "donation_amount" => ["label"=>"Donation Amount","type"=>"number"],
        "currency"        => ["label"=>"Currency","type"=>"text"],
        "event_date"      => ["label"=>"Date","type"=>"date"],
        "status"          => ["label"=>"Status","type"=>"select","options"=>["open","done","cancelled"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "type"            => "required|in:event,donation,reunion,contact,other",
            "alumni_name"     => "nullable|string|max:150",
            "graduation_year" => "nullable|string|max:20",
            "contact_phone"   => "nullable|string|max:30",
            "contact_email"   => "nullable|email|max:150",
            "title"           => "required|string|max:200",
            "description"     => "nullable|string",
            "donation_amount" => "nullable|numeric|min:0",
            "currency"        => "nullable|string|max:10",
            "event_date"      => "nullable|date",
            "status"          => "required|in:open,done,cancelled",
        ];
    }
}