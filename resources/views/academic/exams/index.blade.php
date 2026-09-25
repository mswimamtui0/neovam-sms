@extends("layouts.app")
@section("title", "Exams")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Exams</h1>
 <a href="{{ route("academic.exams.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Create Exam</a>
 </div>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Name</th>
 <th class="p-3 text-left">Term</th>
 <th class="p-3 text-left">Level</th>
 <th class="p-3 text-left">Class</th>
 <th class="p-3 text-left">Published</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($exams as $e)
 <tr class="border-b">
 <td class="p-3">{{ $e->name }}</td>
 <td class="p-3">{{ $e->term }}</td>
 <td class="p-3">{{ ucfirst($e->level) }}</td>
 <td class="p-3">{{ $e->classroom?->name ?? "-" }}</td>
 <td class="p-3">{{ $e->published ? "Yes" : "No" }}</td>
 <td class="p-3">
 <a href="{{ route("academic.exams.timetable", $e) }}" class="text-blue-700 hover:underline">Timetable</a>
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