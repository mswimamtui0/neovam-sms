@extends("layouts.app")
@section("title", "Attendance Analytics")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Attendance Analytics</h1>
        <a href="{{ route("admin.analytics.index") }}" class="text-blue-700 hover:underline">← Back</a>
    </div>

    <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
        <input type="date" name="from" value="{{ $from->format("Y-m-d") }}" class="border rounded px-3 py-2">
        <input type="date" name="to"   value="{{ $to->format("Y-m-d") }}"   class="border rounded px-3 py-2">
        <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
    </form>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
            <div class="text-xs text-gray-500">Total Records</div>
            <div class="text-2xl font-bold">{{ number_format($data["total"]) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
            <div class="text-xs text-gray-500">Present</div>
            <div class="text-2xl font-bold text-green-800">{{ number_format($data["present"]) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
            <div class="text-xs text-gray-500">Absent</div>
            <div class="text-2xl font-bold text-red-800">{{ number_format($data["absent"]) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
            <div class="text-xs text-gray-500">Rate</div>
            <div class="text-2xl font-bold text-yellow-800">{{ $data["rate"] }}%</div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">By Class</h2>
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Class</th>
                        <th class="p-2 text-right">Present</th>
                        <th class="p-2 text-right">Absent</th>
                        <th class="p-2 text-right">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data["by_class"] as $row)
                        <tr class="border-b">
                            <td class="p-2 font-semibold">{{ $row["class"]->name }}</td>
                            <td class="p-2 text-right text-green-700">{{ $row["present"] }}</td>
                            <td class="p-2 text-right text-red-700">{{ $row["absent"] }}</td>
                            <td class="p-2 text-right font-bold">{{ $row["rate"] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">Daily Rate</h2>
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Date</th>
                        <th class="p-2 text-right">Present</th>
                        <th class="p-2 text-right">Total</th>
                        <th class="p-2 text-right">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data["by_day"] as $row)
                        <tr class="border-b">
                            <td class="p-2">{{ $row["date"] }}</td>
                            <td class="p-2 text-right">{{ $row["present"] }}</td>
                            <td class="p-2 text-right">{{ $row["total"] }}</td>
                            <td class="p-2 text-right font-bold">{{ $row["rate"] }}%</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-4 text-center text-gray-500">No data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection