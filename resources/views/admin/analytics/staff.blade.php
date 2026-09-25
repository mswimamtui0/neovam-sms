@extends("layouts.app")
@section("title", "Staff Analytics")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Staff Analytics</h1>
        <a href="{{ route("admin.analytics.index") }}" class="text-blue-700 hover:underline">← Back</a>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
            <div class="text-xs text-gray-500">Total Active</div>
            <div class="text-2xl font-bold">{{ $data["total"] }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
            <div class="text-xs text-gray-500">Teachers</div>
            <div class="text-2xl font-bold text-green-800">{{ $data["teachers"] }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
            <div class="text-xs text-gray-500">Support Staff</div>
            <div class="text-2xl font-bold text-purple-800">{{ $data["support"] }}</div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">By Department</h2>
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Department</th>
                        <th class="p-2 text-right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data["by_department"] as $row)
                        <tr class="border-b">
                            <td class="p-2">{{ $row["department"] }}</td>
                            <td class="p-2 text-right font-bold">{{ $row["count"] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="p-4 text-center text-gray-500">No data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">By Staff Type</h2>
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Type</th>
                        <th class="p-2 text-right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data["by_type"] as $row)
                        <tr class="border-b">
                            <td class="p-2">{{ $row["staff_type"] }}</td>
                            <td class="p-2 text-right font-bold">{{ $row["count"] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="p-4 text-center text-gray-500">No data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection