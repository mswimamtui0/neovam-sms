@extends("layouts.app")
@section("title", "Financial Analytics")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Financial Analytics</h1>
        <a href="{{ route("admin.analytics.index") }}" class="text-blue-700 hover:underline">← Back</a>
    </div>

    <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
        <input type="date" name="from" value="{{ $from->format("Y-m-d") }}" class="border rounded px-3 py-2">
        <input type="date" name="to"   value="{{ $to->format("Y-m-d") }}"   class="border rounded px-3 py-2">
        <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
    </form>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
            <div class="text-xs text-gray-500">Invoiced</div>
            <div class="text-2xl font-bold">TZS {{ number_format($data["invoiced"]) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
            <div class="text-xs text-gray-500">Collected</div>
            <div class="text-2xl font-bold text-green-800">TZS {{ number_format($data["collected"]) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
            <div class="text-xs text-gray-500">Collection Rate</div>
            <div class="text-2xl font-bold text-yellow-800">{{ $data["rate"] }}%</div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">Monthly Income</h2>
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Month</th>
                        <th class="p-2 text-right">Payments</th>
                        <th class="p-2 text-right">Total (TZS)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data["monthly"] as $row)
                        <tr class="border-b">
                            <td class="p-2">{{ $row["month"] }}</td>
                            <td class="p-2 text-right">{{ $row["count"] }}</td>
                            <td class="p-2 text-right font-bold">{{ number_format($row["total"]) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="p-4 text-center text-gray-500">No data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">By Payment Method</h2>
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Method</th>
                        <th class="p-2 text-right">Count</th>
                        <th class="p-2 text-right">Total (TZS)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data["methods"] as $row)
                        <tr class="border-b">
                            <td class="p-2">{{ ucfirst(str_replace("_"," ",$row["method"])) }}</td>
                            <td class="p-2 text-right">{{ $row["count"] }}</td>
                            <td class="p-2 text-right font-bold">{{ number_format($row["total"]) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="p-4 text-center text-gray-500">No data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection