<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;

class SmsTemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = SmsTemplate::query();

        if ($request->filled("language")) {
            $query->where("language", $request->language);
        }
        if ($request->filled("key")) {
            $query->where("key", $request->key);
        }

        $templates = $query->orderBy("key")->orderBy("language")->paginate(30);
        $keys = SmsTemplate::distinct()->pluck("key")->sort();

        return view("admin.sms-templates.index", compact("templates","keys"));
    }

    public function edit(SmsTemplate $smsTemplate)
    {
        return view("admin.sms-templates.edit", compact("smsTemplate"));
    }

    public function update(Request $request, SmsTemplate $smsTemplate)
    {
        $data = $request->validate([
            "name"        => "required|string|max:150",
            "body"        => "required|string|max:600",
            "description" => "nullable|string|max:255",
            "is_active"   => "nullable|boolean",
        ]);

        $data["is_active"] = $request->boolean("is_active");
        $smsTemplate->update($data);

        return redirect()->route("admin.sms-templates.index")
            ->with("success", "Template updated.");
    }

    public function create()
    {
        return view("admin.sms-templates.create");
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "key"         => "required|string|max:50",
            "name"        => "required|string|max:150",
            "language"    => "required|in:en,sw",
            "body"        => "required|string|max:600",
            "description" => "nullable|string|max:255",
        ]);

        $data["school_id"] = \App\Models\School::first()?->id;
        $data["is_active"] = true;

        SmsTemplate::create($data);

        return redirect()->route("admin.sms-templates.index")
            ->with("success", "Template created.");
    }

    public function destroy(SmsTemplate $smsTemplate)
    {
        $smsTemplate->delete();
        return back()->with("success", "Template removed.");
    }
}