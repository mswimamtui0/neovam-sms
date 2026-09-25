<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Finance\IncomeTrackingService;
use Illuminate\Http\Request;

class IncomeTrackingController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->filled("from")
            ? \Carbon\Carbon::parse($request->from)->startOfDay()
            : now()->startOfYear();
        $to = $request->filled("to")
            ? \Carbon\Carbon::parse($request->to)->endOfDay()
            : now()->endOfYear();

        $summary    = IncomeTrackingService::summary($from, $to);
        $byLevel    = IncomeTrackingService::byLevel($from, $to);
        $byClass    = IncomeTrackingService::byClass($from, $to);
        $byTerm     = IncomeTrackingService::byTerm($to->year);
        $dailyIncome = IncomeTrackingService::dailyIncome($from, $to);

        return view("admin.income.index", compact(
            "from","to","summary","byLevel","byClass","byTerm","dailyIncome"
        ));
    }

    public function students(Request $request)
    {
        $from  = $request->filled("from") ? \Carbon\Carbon::parse($request->from) : now()->startOfYear();
        $to    = $request->filled("to")   ? \Carbon\Carbon::parse($request->to)   : now()->endOfYear();
        $level = $request->query("level");

        $rows = IncomeTrackingService::perStudent($from, $to, $level);

        // Sort by collected descending
        usort($rows, fn($a,$b) => $b["collected"] <=> $a["collected"]);

        $topPayers  = array_slice($rows, 0, 20);

        // Sort by outstanding descending for defaulters
        usort($rows, fn($a,$b) => $b["outstanding"] <=> $a["outstanding"]);
        $defaulters = array_filter(array_slice($rows, 0, 20), fn($r) => $r["outstanding"] > 0);

        $levels = ["nursery","kg","pre_unit","primary","secondary","alevel"];

        return view("admin.income.students", compact(
            "from","to","level","levels","topPayers","defaulters"
        ));
    }
}