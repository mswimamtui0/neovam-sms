@extends("layouts.app")
@section("title", "Class Teachers")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Class Teachers</h1>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Class</th>
                    <th class="p-3 text-left">Level</th>
                    <th class="p-3 text-left">Stream</th>
                    <th class="p-3 text-left">Class Teacher</th>
                    <th class="p-3 text-left">Students</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classrooms as $c)
                    <tr class="border-b">
                        <td class="p-3">{{ $c->name }}</td>
                        <td class="p-3">{{ ucfirst($c->level) }}</td>
                        <td class="p-3">{{ $c->stream ?? "-" }}</td>
                        <td class="p-3 font-semibold">
                            {{ $c->classTeacher?->full_name ?? "Not assigned" }}
                        </td>
                        <td class="p-3">{{ $c->students->count() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-gray-500">No classes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection