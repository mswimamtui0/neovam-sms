@extends("layouts.app")
@section("title", "Class Meetings")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Class Meetings — {{ $class->name }}</h1>
        <a href="{{ route("class-teacher.meetings.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add Meeting</a>
    </div>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Topic</th>
                    <th class="p-3 text-left">Notes</th>
                    <th class="p-3 text-left">Decisions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($meetings as $m)
                    <tr class="border-b">
                        <td class="p-3">{{ $m->meeting_date->format("Y-m-d") }}</td>
                        <td class="p-3">{{ $m->topic }}</td>
                        <td class="p-3">{{ \Illuminate\Support\Str::limit($m->notes, 60) }}</td>
                        <td class="p-3">{{ \Illuminate\Support\Str::limit($m->decisions, 40) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-6 text-center text-gray-500">No meetings recorded.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $meetings->links() }}</div>
@endsection