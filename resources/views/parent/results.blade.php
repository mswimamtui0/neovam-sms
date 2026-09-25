@extends("layouts.app")
@section("title", "Children Results")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Children Results</h1>

    @foreach($children as $child)
        <div class="bg-white rounded shadow p-6 mb-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">{{ $child->full_name }}</h2>

            @php
                $byExam = $child->results->groupBy("exam_id");
            @endphp

            @forelse($byExam as $examId => $results)
                @php
                    $first = $results->first();
                    $exam = $first->exam;
                @endphp
                <div class="border rounded p-4 mb-4">
                    <div class="flex justify-between mb-2">
                        <div class="font-semibold">{{ $exam?->name }} ({{ $exam?->term }})</div>
                        <div class="text-sm">
                            @if($exam?->published)
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded">Published</span>
                            @endif
                        </div>
                    </div>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 text-left">Subject</th>
                                <th class="p-2 text-left">Marks</th>
                                <th class="p-2 text-left">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $r)
                                <tr class="border-b">
                                    <td class="p-2">{{ $r->subject }}</td>
                                    <td class="p-2">{{ $r->marks }}</td>
                                    <td class="p-2 font-bold">{{ $r->grade }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-sm mt-2">
                        <strong>Average:</strong> {{ $first->average }}% &nbsp; | &nbsp;
                        <strong>Position:</strong> {{ $first->position }}/{{ $first->class_size }}
                    </div>
                    <div class="mt-3">
                                    <a href="{{ route("parent.report-card", [$child, $exam]) }}" target="_blank"
                                       class="bg-blue-900 text-white text-xs px-3 py-1 rounded">Download Report Card (PDF)</a>
                                </div>
</div>
            @empty
                <p class="text-gray-500">No results yet.</p>
            @endforelse
        </div>
    @endforeach
@endsection