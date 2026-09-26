<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Services\CrossDepartmentFlowService;
use App\Services\DepartmentSmsService;
use Illuminate\Http\Request;

abstract class BaseDepartmentController extends Controller
{
    protected string $departmentCode = "";
    protected string $modelClass = "";
    protected string $viewFolder = "";
    protected array $tableColumns = [];
    protected array $formFields = [];

    protected function department(): ?Department
    {
        return Department::where("code", $this->departmentCode)->first();
    }

    public function index(Request $request)
    {
        $department = $this->department();
        $model      = new $this->modelClass;

        $query = $model->newQuery()->latest();

        if ($request->filled("q")) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                foreach ($this->tableColumns as $col) {
                    $sub->orWhere($col, "LIKE", "%{$q}%");
                }
            });
        }

        if ($request->filled("status")) {
            $query->where("status", $request->status);
        }

        $records = $query->paginate(25)->withQueryString();

        return view($this->viewFolder . ".index", [
            "department"   => $department,
            "records"      => $records,
            "tableColumns" => $this->tableColumns,
            "formFields"   => $this->formFields,
        ]);
    }

    public function create()
    {
        return view($this->viewFolder . ".create", [
            "department" => $this->department(),
            "formFields" => $this->formFields,
            "record"     => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $data["school_id"]     = \App\Models\School::first()?->id;
        $data["department_id"] = $this->department()?->id;

        $model = $this->modelClass::create($data);

        // ============ AUTO-FLOW + SMS ============
        try {
            CrossDepartmentFlowService::flow($this->departmentCode, $model);
            $this->dispatchSms($model);
        } catch (\Throwable $e) {
            \Log::error("Dept afterCreate failed", [
                "dept"  => $this->departmentCode,
                "error" => $e->getMessage(),
            ]);
        }

        return redirect()->route("dept.{$this->departmentCode}.index")
            ->with("success", "Record added.");
    }

    public function show($id)
    {
        $record = $this->modelClass::findOrFail($id);

        return view($this->viewFolder . ".show", [
            "department"   => $this->department(),
            "record"       => $record,
            "tableColumns" => $this->tableColumns,
        ]);
    }

    public function edit($id)
    {
        $record = $this->modelClass::findOrFail($id);

        return view($this->viewFolder . ".create", [
            "department" => $this->department(),
            "formFields" => $this->formFields,
            "record"     => $record,
        ]);
    }

    public function update(Request $request, $id)
    {
        $record = $this->modelClass::findOrFail($id);
        $data   = $request->validate($this->rules($record->id));

        $record->update($data);

        return redirect()->route("dept.{$this->departmentCode}.index")
            ->with("success", "Record updated.");
    }

    public function destroy($id)
    {
        $record = $this->modelClass::findOrFail($id);
        $record->delete();

        return back()->with("success", "Record deleted.");
    }

    protected function rules(?int $ignoreId = null): array
    {
        return [];
    }

    /**
     * Fire the correct SMS for this department.
     */
    protected function dispatchSms($model): void
    {
        $sms = app(DepartmentSmsService::class);

        $method = match ($this->departmentCode) {
            "health"         => "healthLogged",
            "discipline"     => "disciplineLogged",
            "sports"         => "sportsLogged",
            "duty"           => "dutyLogged",
            "boarding"       => "boardingLogged",
            "feeding"        => "feedingLogged",
            "library"        => "libraryLogged",
            "guidance"       => "guidanceLogged",
            "environment"    => "environmentLogged",
            "security"       => "securityLogged",
            "finance"        => "financeLogged",
            "administration" => "administrationLogged",
            default          => null,
        };

        if ($method && method_exists($sms, $method)) {
            $sms->$method($model);
        }

        // ============ COMBINED HUB SMS ============
        try {
            $hub = app(CombinedHubSmsService::class);

            match ($this->departmentCode) {
                "boarding"  => $this->departmentCode === "boarding"
                    ? (($model->type ?? "") === "roll_call"
                        ? $hub->boardingRollCall($model)
                        : (($model->type ?? "") === "incident"
                            ? $hub->boardingNightIncident($model)
                            : null))
                    : null,
                "sports"    => ($model->event_type ?? "") === "match"
                    ? $hub->sportsMatchResult($model)
                    : null,
                "transport" => match ($model->status ?? "") {
                    "in_progress" => $hub->transportTripStarted($model),
                    "cancelled"   => $hub->transportTripDelayed($model),
                    "breakdown"   => $hub->transportBusBreakdown($model),
                    default       => null,
                },
                default     => null,
            };
        } catch (\Throwable $e) {
            \Log::error("Hub SMS dispatch failed", ["dept" => $this->departmentCode, "error" => $e->getMessage()]);
        }
    }
}