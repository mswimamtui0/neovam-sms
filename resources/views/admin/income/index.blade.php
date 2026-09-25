@extends("layouts.app")
@section("title", "Income Tracking")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Income Tracking</h1>
        <a href="{{ route("admin.income-tracking.students") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Per Student</a>
    </div>

    {{-- Date filter --}}
    <form method="GET" class="bg-white rounded shadow p-4 mb-6 flex gap-3">
        <input type="date" name="from" value="{{ $from->format("Y-m-d") }}" class="border rounded px-3 py-2">
        <input type="date" name="to"   value="{{ $to->format("Y-m-d") }}"   class="border rounded px-3 py-2">
        <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route("admin.income-tracking.index") }}" class="text-gray-600 px-4 py-2">Reset</a>
    </form>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
            <div class="text-xs text-gray-500">Total Invoiced</div>
            <div class="text-2xl font-bold text-blue-900">TZS {{ number_format($summary["invoiced"]) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
            <div class="text-xs text-gray-500">Total Collected</div>
            <div class="text-2xl font-bold text-green-900">TZS {{ number_format($summary["collected"]) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
            <div class="text-xs text-gray-500">Outstanding</div>
            <div class="text-2xl font-bold text-red-900">TZS {{ number_format($summary["outstanding"]) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
            <div class="text-xs text-gray-500">Collection Rate</div>
            <div class="text-2xl font-bold text-yellow-900">{{ $summary["rate"] }}%</div>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-3">
            <div class="text-xs text-gray-500">Total Invoices</div>
            <div class="text-lg font-bold">{{ number_format($summary["total_invoices"]) }}</div>
        </div>
        <div class="bg-white rounded shadow p-3">
            <div class="text-xs text-gray-500">Defaulters</div>
            <div class="text-lg font-bold text-red-700">{{ $summary["defaulters"] }}</div>
        </div>
        <div class="bg-white rounded shadow p-3">
            <div class="text-xs text-gray-500">Avg Invoice</div>
            <div class="text-lg font-bold">TZS {{ number_format($summary["avg_invoice"]) }}</div>
        </div>
        <div class="bg-white rounded shadow p-3">
            <div class="text-xs text-gray-500">Period</div>
            <div class="text-xs font-bold">{{ $from->format("d M Y") }} → {{ $to->format("d M Y") }}</div>
        </div>
    </div>

    {{-- By Level --}}
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-blue-900 mb-3">Income by Level</h2>
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Level</th>
                    <th class="p-2 text-right">Students</th>
                    <th class="p-2 text-right">Invoiced</th>
                    <th class="p-2 text-right">Collected</th>
                    <th class="p-2 text-right">Outstanding</th>
                    <th class="p-2 text-right">Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($byLevel as $level => $data)
                    <tr class="border-b">
                        <td class="p-2 font-semibold">{{ \App\Services\Level\SchoolLevelService::label($level) }}</td>
                        <td class="p-2 text-right">{{ $data["students"] }}</td>
                        <td class="p-2 text-right">{{ number_format($data["invoiced"]) }}</td>
                        <td class="p-2 text-right text-green-700 font-bold">{{ number_format($data["collected"]) }}</td>
                        <td class="p-2 text-right text-red-700 font-bold">{{ number_format($data["outstanding"]) }}</td>
                        <td class="p-2 text-right">{{ $data["collection_rate"] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- By Class --}}
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-blue-900 mb-3">Income by Class</h2>
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Class</th>
                    <th class="p-2 text-left">Class Teacher</th>
                    <th class="p-2 text-right">Students</th>
                    <th class="p-2 text-right">Invoiced</th>
                    <th class="p-2 text-right">Collected</th>
                    <th class="p-2 text-right">Outstanding</th>
                    <th class="p-2 text-right">Rate</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byClass as $row)
                    <tr class="border-b">
                        <td class="p-2 font-semibold">{{ $row["class"]->name }}</td>
                        <td class="p-2 text-xs">{{ $row["class"]->classTeacher?->full_name ?? "-" }}</td>
                        <td class="p-2 text-right">{{ $row["students"] }}</td>
                        <td class="p-2 text-right">{{ number_format($row["invoiced"]) }}</td>
                        <td class="p-2 text-right text-green-700 font-bold">{{ number_format($row["collected"]) }}</td>
                        <td class="p-2 text-right text-red-700 font-bold">{{ number_format($row["outstanding"]) }}</td>
                        <td class="p-2 text-right">{{ $row["rate"] }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-6 text-center text-gray-500">No classes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- By Term --}}
    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">Income by Term ({{ $to->year }})</h2>
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Term</th>
                        <th class="p-2 text-right">Invoiced</th>
                        <th class="p-2 text-right">Collected</th>
                        <th class="p-2 text-right">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($byTerm as $term => $data)
                        <tr class="border-b">
                            <td class="p-2">{{ $term }}</td>
                            <td class="p-2 text-right">{{ number_format($data["invoiced"]) }}</td>
                            <td class="p-2 text-right text-green-700 font-bold">{{ number_format($data["collected"]) }}</td>
                            <td class="p-2 text-right">{{ $data["rate"] }}%</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-4 text-center text-gray-500">No terms.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">Daily Income</h2>
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Day</th>
                        <th class="p-2 text-right">Payments</th>
                        <th class="p-2 text-right">Total (TZS)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyIncome as $row)
                        <tr class="border-b">
                            <td class="p-2">{{ $row["day"] }}</td>
                            <td class="p-2 text-right">{{ $row["count"] }}</td>
                            <td class="p-2 text-right font-bold">{{ number_format($row["total"]) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="p-4 text-center text-gray-500">No payments in period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection