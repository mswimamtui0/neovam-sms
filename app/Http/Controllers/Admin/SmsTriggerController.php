<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsTriggerSetting;
use Illuminate\Http\Request;

class SmsTriggerController extends Controller
{
 public function index()
 {
 $triggers = SmsTriggerSetting::orderBy("category")->orderBy("trigger_key")->get();

 $byCategory = $triggers->groupBy("category");

 $stats = [
 "total" => $triggers->count(),
 "enabled" => $triggers->where("is_enabled", true)->count(),
 "disabled" => $triggers->where("is_enabled", false)->where("is_critical", false)->count(),
 "critical" => $triggers->where("is_critical", true)->count(),
 ];

 return view("admin.sms-triggers.index", compact("byCategory","stats"));
 }

 public function update(Request $request, SmsTriggerSetting $trigger)
 {
 if ($trigger->is_critical) {
 return back()->with("error","Critical triggers cannot be disabled.");
 }

 $trigger->update(["is_enabled" => $request->boolean("is_enabled")]);

 return back()->with("success", "{$trigger->label} " . ($trigger->is_enabled ? "enabled" : "disabled") . ".");
 }

 public function bulkToggle(Request $request)
 {
 $data = $request->validate([
 "category" => "required|string",
 "enable" => "required|boolean",
 ]);

 SmsTriggerSetting::where("category", $data["category"])
 ->where("is_critical", false)
 ->update(["is_enabled" => $data["enable"]]);

 return back()->with("success", "Category updated.");
 }
}