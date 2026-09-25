@extends("layouts.app")
@section("title", "Classes Overview")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Classes Overview — {{ now()->format("l, d M Y") }}</h1>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Class</th>
 <th class="p-3 text-left">Class Teacher</th>
 <th class="p-3 text-left">Students</th>
 <th class="p-3 text-left">Present</th>
 <th class="p-3 text-left">Absent</th>
 <th class="p-3 text-left">Late</th>
 <th class="p-3 text-left">Not Marked</th>
 </tr>
 </thead>
 <tbody>
 @foreach($classrooms as $c)
 <tr class="border-b">
 <td class="p-3 font-semibold">{{ $c->name }}</td>
 <td class="p-3">{{ $c->classTeacher?->full_name ?? "-" }}</td>
 <td class="p-3">{{ $c->total_students }}</td>
 <td class="p-3 text-green-700 font-bold">{{ $c->present_today }}</td>
 <td class="p-3 text-red-700 font-bold">{{ $c->absent_today }}</td>
 <td class="p-3 text-yellow-700 font-bold">{{ $c->late_today }}</td>
 <td class="p-3 text-gray-500">{{ $c->not_marked }}</td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>
@endsection