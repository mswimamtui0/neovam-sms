@extends("layouts.app")
@section("title", "Report Cards — " . $exam->name)
@section("content")
 <div class="flex justify-between items-center mb-6">
 <div>
 <h1 class="text-3xl font-bold text-blue-900">{{ $exam->name }}</h1>
 <p class="text-gray-600">{{ $exam->term }} | {{ $exam->classroom?->name ?? "All Classes" }}</p>
 </div>
 <div class="flex gap-2">
 <a href="{{ route("academic.report-cards.bulk", $exam) }}" class="bg-green-700 text-white px-4 py-2 rounded">Bulk PDF (All)</a>
 <a href="{{ route("academic.report-cards.index") }}" class="bg-gray-700 text-white px-4 py-2 rounded">Back</a>
 </div>
 </div>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Adm No</th>
 <th class="p-3 text-left">Student</th>
 <th class="p-3 text-left">Class</th>
 <th class="p-3 text-left">Has Results</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($students as $student)
 <tr class="border-b">
 <td class="p-3">{{ $student->admission_no }}</td>
 <td class="p-3">{{ $student->full_name }}</td>
 <td class="p-3">{{ $student->classroom?->name ?? "-" }}</td>
 <td class="p-3">
 @if($student->has_result)
 <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Yes</span>
 @else
 <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded">No</span>
 @endif
 </td>
 <td class="p-3">
 @if($student->has_result)
 <a href="{{ route("academic.report-cards.single", [$exam, $student]) }}" target="_blank"
 class="text-blue-700 hover:underline">View PDF</a>
 <a href="{{ route("academic.report-cards.download", [$exam, $student]) }}"
 class="text-green-700 hover:underline ml-3">Download</a>
 @else
 <span class="text-gray-400">—</span>
 @endif
 </td>
 </tr>
 @empty
 <tr><td colspan="5" class="p-6 text-center text-gray-500">No students.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
@endsection