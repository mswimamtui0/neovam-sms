<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeReminder;
use App\Services\Finance\FeeReminderService;
use Illuminate\Http\Request;

class FeeReminderController extends Controller
{
    public function index()
    {
        $pending = FeeReminderService::pending();
        $stats   = FeeReminderService::stats();

        return view("admin.fee-reminders.index", compact("pending","stats"));
    }

    public function history(Request $request)
    {
        $query = FeeReminder::with(["student","invoice"])->latest();

        if ($request->filled("type")) {
            $query->where("reminder_type", $request->type);
        }
        if ($request->filled("status")) {
            $query->where("sms_sent", $request->status === "sent");
        }

        $reminders = $query->paginate(40);

        return view("admin.fee-reminders.history", compact("reminders"));
    }

    /**
     * Manually send a specific group.
     */
    public function send(Request $request)
    {
        $data = $request->validate([
            "type" => "required|in:before_due,on_due,after_due,overdue_final",
        ]);

        $result = FeeReminderService::sendBatch($data["type"]);

        return back()->with("success",
            "Sent {$result["sent"]} reminders. Skipped: {$result["skipped"]}."
        );
    }

    /**
     * Send all reminders at once.
     */
    public function sendAll()
    {
        $result = FeeReminderService::sendAll();

        return back()->with("success",
            "Total sent: {$result["sent"]} | Skipped: {$result["skipped"]}."
        );
    }
}