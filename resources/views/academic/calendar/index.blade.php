@extends("layouts.app")
@section("title", "Academic Calendar")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Academic Calendar</h1>
 <a href="{{ route("academic.calendar.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add Term</a>
 </div>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Term</th>
 <th class="p-3 text-left">Year</th>
 <th class="p-3 text-left">Term Start</th>
 <th class="p-3 text-left">Term End</th>
 <th class="p-3 text-left">Exam Dates</th>
 </tr>
 </thead>
 <tbody>
 @forelse($events as $e)
 <tr class="border-b">
 <td class="p-3">{{ $e->term }}</td>
 <td class="p-3">{{ $e->year }}</td>
 <td class="p-3">{{ $e->term_start?->format("Y-m-d") }}</td>
 <td class="p-3">{{ $e->term_end?->format("Y-m-d") }}</td>
 <td class="p-3">
 @if($e->exam_start) {{ $e->exam_start->format("Y-m-d") }} @endif
 @if($e->exam_end) – {{ $e->exam_end->format("Y-m-d") }} @endif
 </td>
 </tr>
 @empty
 <tr><td colspan="5" class="p-6 text-center text-gray-500">No calendar entries.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $events->links() }}</div>
@endsection