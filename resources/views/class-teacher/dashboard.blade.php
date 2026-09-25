@extends("layouts.app")
@section("title", "Class Teacher Dashboard")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-2">Class Teacher — {{ $class->name }}</h1>
 <p class="text-gray-600 mb-6">{{ ucfirst($class->level) }} | Stream {{ $class->stream ?? "-" }}</p>

 <div class="grid grid-cols-4 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-sm text-gray-500">Students</div>
 <div class="text-3xl font-bold text-blue-900">{{ $studentCount }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-sm text-gray-500">Present Today</div>
 <div class="text-3xl font-bold text-green-900">{{ $presentToday }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
 <div class="text-sm text-gray-500">Absent Today</div>
 <div class="text-3xl font-bold text-red-900">{{ $absentToday }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-sm text-gray-500">Discipline Logs</div>
 <div class="text-3xl font-bold text-yellow-900">{{ $disciplineCount }}</div>
 </div>
 </div>

 <div class="bg-white rounded shadow p-6">
 <h2 class="text-xl font-bold text-blue-900 mb-4">My Class Students</h2>
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Adm No</th>
 <th class="p-3 text-left">Name</th>
 <th class="p-3 text-left">Parent Phone</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($students as $s)
 <tr class="border-b">
 <td class="p-3">{{ $s->admission_no }}</td>
 <td class="p-3">{{ $s->full_name }}</td>
 <td class="p-3">{{ $s->parent_phone }}</td>
 <td class="p-3">
 <a href="{{ route("class-teacher.students.show", $s) }}" class="text-blue-700 hover:underline">View</a>
 </td>
 </tr>
 @empty
 <tr><td colspan="4" class="p-6 text-center text-gray-500">No students.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
@endsection