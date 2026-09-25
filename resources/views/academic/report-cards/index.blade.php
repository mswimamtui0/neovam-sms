@extends("layouts.app")
@section("title", "Report Cards")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Report Cards (PDF)</h1>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Exam</th>
                    <th class="p-3 text-left">Class</th>
                    <th class="p-3 text-left">Term</th>
                    <th class="p-3 text-left">Published</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($exams as $exam)
                    <tr class="border-b">
                        <td class="p-3 font-semibold">{{ $exam->name }}</td>
                        <td class="p-3">{{ $exam->classroom?->name ?? "All" }}</td>
                        <td class="p-3">{{ $exam->term }}</td>
                        <td class="p-3">
                            @if($exam->published)
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Published</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Draft</span>
                            @endif
                        </td>
                        <td class="p-3">
                            <a href="{{ route("academic.report-cards.exam", $exam) }}" class="text-blue-700 hover:underline">View Students</a>
                            <a href="{{ route("academic.report-cards.bulk", $exam) }}" class="text-green-700 hover:underline ml-3">Bulk PDF</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-gray-500">No exams yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $exams->links() }}</div>
@endsection