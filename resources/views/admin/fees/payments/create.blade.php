@extends("layouts.app")
@section("title", "Record Payment")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Record Payment</h1>

    @if($invoice)
        <div class="bg-blue-50 border-l-4 border-blue-600 p-4 mb-4 rounded">
            <strong>{{ $invoice->student?->full_name }}</strong> — Invoice {{ $invoice->invoice_no }}<br>
            Amount: TZS {{ number_format($invoice->amount) }} | Balance: TZS {{ number_format($invoice->balance) }}
        </div>
    @endif

    <form method="POST" action="{{ route("admin.payments.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
        @csrf
        @if($invoice)
            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
        @else
            <div>
                <label class="block font-semibold mb-1">Invoice ID</label>
                <input type="number" name="invoice_id" class="w-full border rounded px-3 py-2" required placeholder="e.g. 1">
            </div>
        @endif

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Amount</label>
                <input type="number" step="0.01" name="amount" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Payment Date</label>
                <input type="date" name="payment_date" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Method</label>
                <select name="method" class="w-full border rounded px-3 py-2" required>
                    <option value="cash">Cash</option>
                    <option value="bank">Bank Transfer</option>
                    <option value="mobile_money">Mobile Money</option>
                    <option value="cheque">Cheque</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Reference (optional)</label>
                <input type="text" name="reference" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" rows="2" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded">Save Payment + Send SMS</button>
    </form>
@endsection