@extends("layouts.app")
@section("title", "Payments")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Payment History</h1>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Receipt</th>
                    <th class="p-3 text-left">Child</th>
                    <th class="p-3 text-left">Amount</th>
                    <th class="p-3 text-left">Method</th>
                    <th class="p-3 text-left">Date</th>
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
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-gray-500">No payments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $payments->links() }}</div>
@endsection