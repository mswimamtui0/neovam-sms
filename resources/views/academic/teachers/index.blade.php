@extends("layouts.app")
@section("title", "Teachers")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Teachers & Assignments</h1>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Department</th>
                    <th class="p-3 text-left">Subjects</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $t)
                    <tr class="border-b">
                        <td class="p-3">{{ $t->full_name }}</td>
                        <td class="p-3">{{ $t->staff_type }}</td>
                        <td class="p-3">{{ $t->department }}</td>
                        <td class="p-3">
                            @foreach($t->subjects as $s)
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $s->code }}</span>
                            @endforeach
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-6 text-center text-gray-500">No teachers.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $teachers->links() }}</div>
@endsection