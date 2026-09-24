@extends("layouts.app")
@section("title", "Class Performance")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Performance — {{ $class->name }}</h1>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Student</th>
                    <th class="p-3 text-left">Subjects</th>
                    <th class="p-3 text-left">Average Marks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $s)
                    <tr class="border-b">
                        <td class="p-3">{{ $s->full_name }}</td>
                        <td class="p-3">{{ $s->results->count() }}</td>
                        <td class="p-3 font-bold">
                            {{ $s->results->count() ? round($s->results->avg("marks"), 1) : "-" }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="p-6 text-center text-gray-500">No students.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection