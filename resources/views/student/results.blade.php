@extends("layouts.app")
@section("title", "My Results")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">My Results</h1>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Exam</th>
                    <th class="p-3 text-left">Subject</th>
                    <th class="p-3 text-left">Marks</th>
                    <th class="p-3 text-left">Grade</th>
                </tr>
            </thead>
            <tbody>
                @forelse($student->results as $r)
                    <tr class="border-b">
                        <td class="p-3">{{ $r->exam?->name }}</td>
                        <td class="p-3">{{ $r->subject }}</td>
                        <td class="p-3">{{ $r->marks }}</td>
                        <td class="p-3">{{ $r->grade }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-6 text-center text-gray-500">No results yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection