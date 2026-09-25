@extends("layouts.app")
@section("title", "Result Sheet")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-2">Result Sheet — {{ $exam->name }}</h1>
 <p class="text-gray-600 mb-6">{{ $exam->term }} | {{ $exam->classroom?->name ?? "All Classes" }}</p>

 @foreach($students as $studentId => $results)
 @php
 $first = $results->first();
 $student = $first->student;
 @endphp
 <div class="bg-white rounded shadow p-4 mb-4">
 <div class="flex justify-between mb-3">
 <div>
 <div class="font-bold text-blue-900 text-lg">{{ $student?->full_name }}</div>
 <div class="text-sm text-gray-500">Adm: {{ $student?->admission_no }}</div>
 </div>
 <div class="text-right text-sm">
 <div><strong>Avg:</strong> {{ $first->average }}%</div>
 <div><strong>Position:</strong> {{ $first->position }}/{{ $first->class_size }}</div>
 </div>
 </div>
 <table class="w-full text-sm">
 <thead class="bg-blue-100">
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
 </div>
 @endforeach
@endsection