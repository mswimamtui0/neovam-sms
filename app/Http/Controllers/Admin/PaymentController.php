<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendPaymentSms;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(["student","invoice"])->latest()->paginate(30);
        $totalToday  = Payment::whereDate("payment_date", now()->toDateString())->sum("amount");
        $totalMonth  = Payment::whereMonth("payment_date", now()->month)->sum("amount");
        $totalAll    = Payment::sum("amount");

        return view("admin.fees.payments.index", compact("payments","totalToday","totalMonth","totalAll"));
    }

    public function create(Request $request)
    {
        $invoice = null;
        if ($request->filled("invoice_id")) {
            $invoice = Invoice::with("student")->find($request->invoice_id);
        }

        return view("admin.fees.payments.create", compact("invoice"));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "invoice_id"   => "required|exists:invoices,id",
            "amount"       => "required|numeric|min:0.01",
            "method"       => "required|in:cash,bank,mobile_money,cheque",
            "reference"    => "nullable|string|max:100",
            "payment_date" => "required|date",
            "notes"        => "nullable|string",
        ]);

        $invoice = Invoice::findOrFail($data["invoice_id"]);

        $data["student_id"]  = $invoice->student_id;
        $data["recorded_by"] = auth()->id();
        $data["receipt_no"]  = "RCP-" . strtoupper(uniqid());

        $payment = Payment::create($data);

        // Refresh invoice balance
        $invoice->refreshBalance();

        // Send SMS to parent
        $student = $invoice->student;
        if ($student && $student->parent_phone) {
            SendPaymentSms::dispatch(
                $student->parent_phone,
                $student->full_name,
                (float) $payment->amount,
                (float) $invoice->fresh()->balance,
                $payment->receipt_no
            );
            $payment->update(["sms_sent" => true]);
        }

        return redirect()->route("admin.invoices.show", $invoice)
            ->with("success", "Payment recorded. SMS sent to parent.");
    }
}