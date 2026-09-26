<?php
namespace App\Http\Controllers\Department;
use App\Models\TransportTrip;

class TransportController extends BaseDepartmentController {
    protected string $departmentCode = "transport";
    protected string $modelClass     = TransportTrip::class;
    protected string $viewFolder     = "departments.transport";

    protected array $tableColumns = ["bus_code","route_name","driver_name","trip_date","direction","status","recorded_by"];

    protected array $formFields = [
        "bus_code"       => ["label"=>"Bus Code","type"=>"text","required"=>true],
        "route_name"     => ["label"=>"Route","type"=>"text"],
        "driver_name"    => ["label"=>"Driver","type"=>"text"],
        "conductor_name" => ["label"=>"Conductor","type"=>"text"],
        "student_count"  => ["label"=>"Students","type"=>"number"],
        "trip_date"      => ["label"=>"Date","type"=>"date","required"=>true],
        "trip_time"      => ["label"=>"Time","type"=>"time"],
        "direction"      => ["label"=>"Direction","type"=>"select","options"=>["to_school","from_school","other"]],
        "fuel_used"      => ["label"=>"Fuel Used (L)","type"=>"number"],
        "notes"          => ["label"=>"Notes","type"=>"textarea"],
        "status"         => ["label"=>"Status","type"=>"select","options"=>["scheduled","in_progress","completed","cancelled"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "bus_code"       => "required|string|max:50",
            "route_name"     => "nullable|string|max:150",
            "driver_name"    => "nullable|string|max:150",
            "conductor_name" => "nullable|string|max:150",
            "student_count"  => "nullable|integer|min:0",
            "trip_date"      => "required|date",
            "trip_time"      => "nullable|string|max:20",
            "direction"      => "required|in:to_school,from_school,other",
            "fuel_used"      => "nullable|numeric|min:0",
            "notes"          => "nullable|string",
            "status"         => "required|in:scheduled,in_progress,completed,cancelled",
        ];
    }
}