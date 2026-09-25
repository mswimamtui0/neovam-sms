@extends("layouts.app")
@section("title", "Exams & Results")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Exams &amp; Results</h1>
 <a href="{{ route("admin.results.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add Result</a>
 </div>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Exam</th>
 <th class="p-3 text-left">Class</th>
 <th class="p-3 text-left">Term</th>
 <th class="p-3 text-left">Results</th>
 <th class="p-3 text-left">Status</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($exams as $exam)
 <tr class="border-b">
 <td class="p-3 font-semibold">{{ $exam->name }}</td>
 <td class="p-3">{{ $exam->classroom?->name ?? "All" }}</td>
 <td class="p-3">{{ $exam->term }}</td>
 <td class="p-3">{{ $exam->results->count() }}</td>
 <td class="p-3">
 @if($exam->published)
 <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Published</span>
 @else
 <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Draft</span>
 @endif
 </td>
 <td class="p-3">
 <a href="{{ route("admin.results.show", $exam) }}" class="text-blue-700 hover:underline">View</a>
 @if(!$exam->published && $exam->results->count() > 0)
 <form method="POST" action="{{ route("admin.results.publish", $exam) }}" class="inline ml-2" onsubmit="return confirm(&quot;Publish results and send SMS to all parents?&quot;);">
 @csrf
 <button class="text-green-700 hover:underline">Publish + SMS</button>
 </form>
 @endif
 </td>
 </tr>
 @empty
 <tr><td colspan="6" class="p-6 text-center text-gray-500">No exams yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $exams->links() }}</div>
@endsection