@extends("layouts.app")
@section("title", "Attendance Overview")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Attendance Overview</h1>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
 <input type="date" name="date" value="{{ $date }}" class="border rounded px-3 py-2">
 <button class="bg-blue-900 text-white px-4 py-2 rounded">View</button>
 </form>

 <div class="grid grid-cols-3 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4">
 <div class="text-sm text-gray-500">Total Students</div>
 <div class="text-2xl font-bold">{{ $totalStudents }}</div>
 </div>
 <div class="bg-white rounded shadow p-4">
 <div class="text-sm text-gray-500">Present</div>
 <div class="text-2xl font-bold text-green-700">{{ $totalPresent }}</div>
 </div>
 <div class="bg-white rounded shadow p-4">
 <div class="text-sm text-gray-500">Absent</div>
 <div class="text-2xl font-bold text-red-700">{{ $totalAbsent }}</div>
 </div>
 </div>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Class</th>
 <th class="p-3 text-left">Total</th>
 <th class="p-3 text-left">Present</th>
 <th class="p-3 text-left">Absent</th>
 <th class="p-3 text-left">Late</th>
 <th class="p-3 text-left">Unmarked</th>
 </tr>
 </thead>
 <tbody>
 @foreach($classrooms as $c)
 <tr class="border-b">
 <td class="p-3 font-semibold">{{ $c->name }}</td>
 <td class="p-3">{{ $c->total }}</td>
 <td class="p-3 text-green-700 font-bold">{{ $c->present }}</td>
 <td class="p-3 text-red-700 font-bold">{{ $c->absent }}</td>
 <td class="p-3 text-yellow-700 font-bold">{{ $c->late }}</td>
 <td class="p-3 text-gray-500">{{ $c->unmarked }}</td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>
@endsection