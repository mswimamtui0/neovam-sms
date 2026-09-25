@extends("layouts.app")
@section("title", "Income Per Student")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Income Per Student</h1>
        <a href="{{ route("admin.income-tracking.index") }}" class="text-blue-700 hover:underline">← Back</a>
    </div>

    <form method="GET" class="bg-white rounded shadow p-4 mb-6 flex gap-3">
        <input type="date" name="from" value="{{ $from->format("Y-m-d") }}" class="border rounded px-3 py-2">
        <input type="date" name="to"   value="{{ $to->format("Y-m-d") }}"   class="border rounded px-3 py-2">
        <select name="level" class="border rounded px-3 py-2">
            <option value="">All Levels</option>
            @foreach($levels as $l)
                <option value="{{ $l }}" {{ $level === $l ? "selected" : "" }}>{{ ucfirst($l) }}</option>
            @endforeach
        </select>
        <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route("admin.income-tracking.students") }}" class="text-gray-600 px-4 py-2">Reset</a>
    </form>

    <div class="grid grid-cols-2 gap-6">
        {{-- Top Payers --}}
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-green-900 mb-3">Top 20 Payers</h2>
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Student</th>
                        <th class="p-2 text-left">Class</th>
                        <th class="p-2 text-right">Collected</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topPayers as $row)
                        <tr class="border-b">
                            <td class="p-2">{{ $row["student"]->full_name }}</td>
                            <td class="p-2 text-xs">{{ $row["student"]->classroom?->name ?? "-" }}</td>
                            <td class="p-2 text-right font-bold text-green-700">TZS {{ number_format($row["collected"]) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="p-4 text-center text-gray-500">No data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Defaulters --}}
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-red-900 mb-3">Top 20 Defaulters</h2>
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Student</th>
                        <th class="p-2 text-left">Class</th>
                        <th class="p-2 text-right">Outstanding</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($defaulters as $row)
                        <tr class="border-b">
                            <td class="p-2">{{ $row["student"]->full_name }}</td>
                            <td class="p-2 text-xs">{{ $row["student"]->classroom?->name ?? "-" }}</td>
                            <td class="p-2 text-right font-bold text-red-700">TZS {{ number_format($row["outstanding"]) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="p-4 text-center text-gray-500">No defaulters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection