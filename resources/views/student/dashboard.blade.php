@extends("layouts.app")
@section("title", "Student Dashboard")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Welcome, {{ $student->full_name }}</h1>

 <div class="grid grid-cols-3 gap-4">
 <div class="bg-white rounded shadow p-4">
 <div class="text-sm text-gray-500">Class</div>
 <div class="text-xl font-bold">{{ $student->classroom?->name ?? "-" }}</div>
 </div>
 <div class="bg-white rounded shadow p-4">
 <div class="text-sm text-gray-500">Level</div>
 <div class="text-xl font-bold">{{ ucfirst($student->level) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4">
 <div class="text-sm text-gray-500">Admission No</div>
 <div class="text-xl font-bold">{{ $student->admission_no }}</div>
 </div>
 </div>

 <div class="mt-6 flex gap-3">
 <a href="{{ route("student.results") }}" class="bg-blue-900 text-white px-4 py-2 rounded">My Results</a>
 <a href="{{ route("student.attendance") }}" class="bg-blue-900 text-white px-4 py-2 rounded">My Attendance</a>
 </div>
@endsection