<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsBalanceTransaction;
use App\Models\SmsPricingRule;
use App\Services\Sms\SmsCostService;
use Illuminate\Http\Request;

class SmsCostController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->filled("from")
            ? \Carbon\Carbon::parse($request->from)->startOfDay()
            : now()->startOfMonth();
        $to = $request->filled("to")
            ? \Carbon\Carbon::parse($request->to)->endOfDay()
            : now()->endOfMonth();

        $summary = SmsCostService::summary($from, $to);

        $balanceUnits = SmsBalanceTransaction::balanceUnits();
        $balanceMoney = SmsBalanceTransaction::balanceMoney();

        $pricing = SmsPricingRule::current();

        return view("admin.sms-cost.index", compact(
            "summary","balanceUnits","balanceMoney","pricing","from","to"
        ));
    }

    /* ============ PRICING RULES ============ */
    public function pricing()
    {
        $rules = SmsPricingRule::orderByDesc("effective_from")->paginate(20);
        return view("admin.sms-cost.pricing", compact("rules"));
    }

    public function storePricing(Request $request)
    {
        $data = $request->validate([
            "name"           => "required|string|max:100",
            "unit_price"     => "required|numeric|min:0",
            "currency"       => "required|string|max:10",
            "effective_from" => "required|date",
            "effective_to"   => "nullable|date|after_or_equal:effective_from",
            "notes"          => "nullable|string",
        ]);

        $data["school_id"] = \App\Models\School::first()?->id;
        $data["is_active"] = true;

        SmsPricingRule::create($data);

        return back()->with("success","Pricing rule added.");
    }

    public function destroyPricing(SmsPricingRule $pricingRule)
    {
        $pricingRule->delete();
        return back()->with("success","Pricing rule removed.");
    }

    /* ============ TOP-UP ============ */
    public function topup(Request $request)
    {
        $data = $request->validate([
            "units"       => "required|integer|min:1",
            "amount"      => "required|numeric|min:0",
            "reference"   => "nullable|string|max:100",
            "description" => "nullable|string|max:255",
        ]);

        SmsCostService::topup(
            $data["units"],
            (float) $data["amount"],
            $data["reference"] ?? null,
            $data["description"] ?? null
        );

        return back()->with("success","Balance topped up by {$data["units"]} units.");
    }

    /* ============ TRANSACTIONS ============ */
    public function transactions(Request $request)
    {
        $query = SmsBalanceTransaction::with("creator")->latest();

        if ($request->filled("type")) {
            $query->where("type", $request->type);
        }

        $transactions = $query->paginate(30);
        $balanceUnits = SmsBalanceTransaction::balanceUnits();
        $balanceMoney = SmsBalanceTransaction::balanceMoney();

        return view("admin.sms-cost.transactions", compact("transactions","balanceUnits","balanceMoney"));
    }
}