<?php

namespace App\Services\Finance;

use App\Models\PayrollItem;
use App\Models\PayrollRun;
use App\Models\SalaryStructure;
use App\Models\School;
use App\Models\Staff;
use Carbon\Carbon;

class PayrollService
{
    /**
     * Create a new payroll run for a period.
     * Returns the PayrollRun with all items generated.
     */
    public static function generate(string $period, ?string $notes = null): PayrollRun
    {
        // Parse period (YYYY-MM)
        $date = Carbon::createFromFormat("Y-m", $period);
        $periodStart = $date->copy()->startOfMonth();
        $periodEnd   = $date->copy()->endOfMonth();

        $school = School::first();

        $run = PayrollRun::firstOrCreate(
            ["school_id" => $school?->id, "period" => $period],
            [
                "label"        => $date->format("F Y"),
                "period_start" => $periodStart,
                "period_end"   => $periodEnd,
                "status"       => "draft",
                "created_by"   => auth()->id(),
                "notes"        => $notes,
            ]
        );

        // Only add items if this run is fresh
        if ($run->items()->count() === 0) {
            $staffList = Staff::where("status", "active")->get();

            foreach ($staffList as $staff) {
                $structure = SalaryStructure::currentFor($staff->id);
                if (!$structure) continue;

                PayrollItem::create([
                    "payroll_run_id" => $run->id,
                    "staff_id"       => $staff->id,
                    "basic_salary"   => $structure->basic_salary,
                    "allowances"     => $structure->totalAllowances(),
                    "deductions"     => $structure->totalDeductions(),
                    "gross_pay"      => $structure->grossPay(),
                    "net_pay"        => $structure->netPay(),
                    "status"         => "pending",
                ]);
            }
        }

        $run->recalculate();

        return $run;
    }

    /**
     * Approve a payroll run.
     */
    public static function approve(PayrollRun $run): void
    {
        $run->update([
            "status"      => "approved",
            "approved_by" => auth()->id(),
            "approved_at" => now(),
        ]);
    }

    /**
     * Mark a payroll run as paid + mark all items paid.
     */
    public static function markPaid(PayrollRun $run): void
    {
        $run->update([
            "status"  => "paid",
            "paid_at" => now(),
        ]);

        $run->items()->update([
            "status"      => "paid",
            "paid_at"     => now(),
            "amount_paid" => \DB::raw("net_pay"),
        ]);
    }

    /**
     * Mark a single staff payment as paid.
     */
    public static function markItemPaid(PayrollItem $item, ?string $reference = null): void
    {
        $item->update([
            "status"            => "paid",
            "paid_at"           => now(),
            "amount_paid"       => $item->net_pay,
            "payment_reference" => $reference,
        ]);
    }

    /**
     * Payroll summary stats.
     */
    public static function stats(): array
    {
        $latestRuns = PayrollRun::latest()->take(12)->get();

        return [
            "total_runs"       => PayrollRun::count(),
            "runs_this_year"   => PayrollRun::whereYear("created_at", now()->year)->count(),
            "total_paid_ytd"   => (float) PayrollRun::where("status", "paid")
                                        ->whereYear("created_at", now()->year)
                                        ->sum("total_net"),
            "total_pending"    => (float) PayrollRun::whereIn("status", ["draft","approved"])
                                        ->sum("total_net"),
            "active_structures"=> SalaryStructure::where("is_active", true)->count(),
            "latest_runs"      => $latestRuns,
        ];
    }
}