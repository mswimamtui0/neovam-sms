@extends("layouts.app")
@section("title", "Monthly Staff Report")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Monthly Staff Report — {{ $month }}/{{ $year }}</h1>

    <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
        <select name="month" class="border rounded px-3 py-2">
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $month == $m ? "selected" : "" }}>{{ date("F", mktime(0,0,0,$m,1)) }}</option>
            @endfor
        </select>
        <input type="number" name="year" value="{{ $year }}" class="border rounded px-3 py-2">
        <button class="bg-blue-900 text-white px-4 py-2 rounded">View</button>
    </form>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Staff</th>
                    <th class="p-3 text-left">Present</th>
                    <th class="p-3 text-left">Late</th>
                    <th class="p-3 text-left">Absent</th>
                    <th class="p-3 text-left">Total Hours</th>
                    <th class="p-3 text-left">Avg Hours</th>
                    <th class="p-3 text-left">Total Late (min)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                    <tr class="border-b">
                        <td class="p-3 font-semibold">{{ $row["staff"]->full_name }}</td>
                        <td class="p-3 text-green-700 font-bold">{{ $row["present"] }}</td>
                        <td class="p-3 text-yellow-700 font-bold">{{ $row["late"] }}</td>
                        <td class="p-3 text-red-700 font-bold">{{ $row["absent"] }}</td>
                        <td class="p-3">{{ $row["total_hours"] }}h</td>
                        <td class="p-3">{{ $row["avg_hours"] }}h</td>
                        <td class="p-3">{{ $row["total_late_min"] }} min</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-6 text-center text-gray-500">No data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection