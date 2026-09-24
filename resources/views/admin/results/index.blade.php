@extends("layouts.app")
@section("title", "Results")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Exams & Results</h1>
 <a href="{{ route("admin.results.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">+ Add Result</a>
 </div>
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
 <tr class="border-b hover:bg-gray-50">
 <td class="p-3">{{ $exam->name }}</td>
 <td class="p-3">{{ $exam->classroom?->name ?? "-" }}</td>
 <td class="p-3">{{ $exam->term }}</td>
 <td class="p-3">{{ $exam->published ? "Yes" : "No" }}</td>
 <td class="p-3">
 <form method="POST" action="{{ route("admin.results.publish", $exam) }}" class="inline">
 @csrf
 <button class="text-green-700 hover:underline">Publish & SMS Parents</button>
 </form>
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