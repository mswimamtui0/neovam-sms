@extends("layouts.app")
@section("title", "Welfare Notes")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Welfare Notes — {{ $class->name }}</h1>
        <a href="{{ route("class-teacher.welfare.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add Note</a>
    </div>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Student</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Observation</th>
                    <th class="p-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notes as $n)
                    <tr class="border-b">
                        <td class="p-3">{{ $n->note_date->format("Y-m-d") }}</td>
                        <td class="p-3">{{ $n->student?->full_name }}</td>
                        <td class="p-3">{{ ucfirst($n->type) }}</td>
                        <td class="p-3">{{ $n->observation }}</td>
                        <td class="p-3">{{ $n->action ?? "-" }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-gray-500">No welfare notes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $notes->links() }}</div>
@endsection