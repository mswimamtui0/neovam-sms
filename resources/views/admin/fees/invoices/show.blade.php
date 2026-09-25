@extends("layouts.app")
@section("title", "Invoice Details")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Invoice {{ $invoice->invoice_no }}</h1>
 <a href="{{ route("admin.payments.create", ["invoice_id" => $invoice->id]) }}" class="bg-green-700 text-white px-4 py-2 rounded">Record Payment</a>
 </div>

 <div class="grid grid-cols-2 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4">
 <h3 class="font-bold text-blue-900 mb-2">Student</h3>
 <p><strong>Name:</strong> {{ $invoice->student?->full_name }}</p>
 <p><strong>Adm No:</strong> {{ $invoice->student?->admission_no }}</p>
 <p><strong>Parent Phone:</strong> {{ $invoice->student?->parent_phone }}</p>
 </div>
 <div class="bg-white rounded shadow p-4">
 <h3 class="font-bold text-blue-900 mb-2">Invoice</h3>
 <p><strong>Term:</strong> {{ $invoice->term }} {{ $invoice->year }}</p>
 <p><strong>Amount:</strong>TZS {{ number_format($invoice->amount) }}</p>
 <p><strong>Paid:</strong>TZS {{ number_format($invoice->amount_paid) }}</p>
 <p><strong>Balance:</strong>TZS {{ number_format($invoice->balance) }}</p>
 <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
 </div>
 </div>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Receipt #</th>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">Amount</th>
 <th class="p-3 text-left">Method</th>
 <th class="p-3 text-left">SMS</th>
 </tr>
 </thead>
 <tbody>
 @forelse($invoice->payments as $p)
 <tr class="border-b">
 <td class="p-3">{{ $p->receipt_no }}</td>
 <td class="p-3">{{ $p->payment_date->format("Y-m-d") }}</td>
 <td class="p-3">TZS {{ number_format($p->amount) }}</td>
 <td class="p-3">{{ ucfirst($p->method) }}</td>
 <td class="p-3">{{ $p->sms_sent ? "Yes" : "No" }}</td>
 </tr>
 @empty
 <tr><td colspan="5" class="p-6 text-center text-gray-500">No payments yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
@endsection