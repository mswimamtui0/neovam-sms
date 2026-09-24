@extends("layouts.app")
@section("title", "My Students")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">My Students</h1>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Adm No</th>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Class</th>
                    <th class="p-3 text-left">Parent Phone</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $s)
                    <tr class="border-b">
                        <td class="p-3">{{ $s->admission_no }}</td>
                        <td class="p-3">{{ $s->full_name }}</td>
                        <td class="p-3">{{ $s->classroom?->name }}</td>
                        <td class="p-3">{{ $s->parent_phone }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-6 text-center text-gray-500">No students.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $students->links() }}</div>
@endsection