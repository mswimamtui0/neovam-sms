@extends("layouts.app")
@section("title", "Supervision Logs")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Supervision Logs</h1>
    </div>

    <form method="POST" action="{{ route("duty.supervision.log") }}" class="bg-white rounded shadow p-4 mb-6 grid grid-cols-3 gap-3">
        @csrf
        <select name="area" class="border rounded px-3 py-2" required>
            <option value="">-- Select Area --</option>
            @foreach(["assembly","break","lunch","evening","night","gate","exam"] as $a)
                <option value="{{ $a }}">{{ ucfirst($a) }}</option>
            @endforeach
        </select>
        <select name="status" class="border rounded px-3 py-2" required>
            <option value="done">Done</option>
            <option value="missed">Missed</option>
            <option value="issue">Issue</option>
        </select>
        <input type="text" name="notes" placeholder="Notes (optional)" class="border rounded px-3 py-2">
        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded col-span-3">Log Supervision</button>
    </form>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Area</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr class="border-b">
                        <td class="p-3">{{ $log->log_date->format("Y-m-d") }}</td>
                        <td class="p-3">{{ ucfirst($log->area) }}</td>
                        <td class="p-3">{{ ucfirst($log->status) }}</td>
                        <td class="p-3">{{ $log->notes ?? "-" }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-6 text-center text-gray-500">No logs yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $logs->links() }}</div>
@endsection