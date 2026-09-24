@extends("layouts.app")
@section("title", "Discipline Logs")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Discipline Logs — {{ $class->name }}</h1>
        <a href="{{ route("class-teacher.discipline.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add Log</a>
    </div>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Student</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Reason</th>
                    <th class="p-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $l)
                    <tr class="border-b">
                        <td class="p-3">{{ $l->log_date->format("Y-m-d") }}</td>
                        <td class="p-3">{{ $l->student?->full_name }}</td>
                        <td class="p-3">{{ ucfirst($l->type) }}</td>
                        <td class="p-3">{{ $l->reason }}</td>
                        <td class="p-3">{{ $l->action_taken ?? "-" }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-gray-500">No records.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $logs->links() }}</div>
@endsection