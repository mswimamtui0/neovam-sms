@extends("layouts.app")
@section("title", "Payments")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Payments</h1>
        <a href="{{ route("admin.payments.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Record Payment</a>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
            <div class="text-sm text-gray-500">Today</div>
            <div class="text-2xl font-bold">TZS {{ number_format($totalToday) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
            <div class="text-sm text-gray-500">This Month</div>
            <div class="text-2xl font-bold">TZS {{ number_format($totalMonth) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
            <div class="text-sm text-gray-500">All Time</div>
            <div class="text-2xl font-bold">TZS {{ number_format($totalAll) }}</div>
        </div>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Receipt #</th>
                    <th class="p-3 text-left">Student</th>
                    <th class="p-3 text-left">Amount</th>
                    <th class="p-3 text-left">Method</th>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">SMS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                    <tr class="border-b">
                        <td class="p-3">{{ $p->receipt_no }}</td>
                        <td class="p-3">{{ $p->student?->full_name }}</td>
                        <td class="p-3 font-bold">TZS {{ number_format($p->amount) }}</td>
                        <td class="p-3">{{ ucfirst(str_replace("_"," ",$p->method)) }}</td>
                        <td class="p-3">{{ $p->payment_date->format("Y-m-d") }}</td>
                        <td class="p-3">{{ $p->sms_sent ? "Yes" : "No" }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-6 text-center text-gray-500">No payments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $payments->links() }}</div>
@endsection