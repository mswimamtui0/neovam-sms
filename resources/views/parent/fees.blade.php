@extends("layouts.app")
@section("title", "Fees & Invoices")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Fees &amp; Invoices</h1>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
            <div class="text-sm text-gray-500">Total Billed</div>
            <div class="text-2xl font-bold">TZS {{ number_format($totals["billed"]) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
            <div class="text-sm text-gray-500">Total Paid</div>
            <div class="text-2xl font-bold">TZS {{ number_format($totals["paid"]) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
            <div class="text-sm text-gray-500">Outstanding</div>
            <div class="text-2xl font-bold">TZS {{ number_format($totals["balance"]) }}</div>
        </div>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Invoice</th>
                    <th class="p-3 text-left">Child</th>
                    <th class="p-3 text-left">Term</th>
                    <th class="p-3 text-left">Amount</th>
                    <th class="p-3 text-left">Paid</th>
                    <th class="p-3 text-left">Balance</th>
                    <th class="p-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                    <tr class="border-b">
                        <td class="p-3">{{ $inv->invoice_no }}</td>
                        <td class="p-3">{{ $inv->student?->full_name }}</td>
                        <td class="p-3">{{ $inv->term }} {{ $inv->year }}</td>
                        <td class="p-3">{{ number_format($inv->amount) }}</td>
                        <td class="p-3 text-green-700">{{ number_format($inv->amount_paid) }}</td>
                        <td class="p-3 font-bold {{ $inv->balance > 0 ? "text-red-600" : "text-green-600" }}">
                            {{ number_format($inv->balance) }}
                        </td>
                        <td class="p-3">{{ ucfirst($inv->status) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-6 text-center text-gray-500">No invoices.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $invoices->links() }}</div>
@endsection