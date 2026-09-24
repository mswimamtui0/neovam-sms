@extends("layouts.app")
@section("title", "Students")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Students</h1>
 <a href="{{ route("admin.students.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">+ Admit Student</a>
 </div>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Adm No</th>
 <th class="p-3 text-left">Name</th>
 <th class="p-3 text-left">Level</th>
 <th class="p-3 text-left">Class</th>
 <th class="p-3 text-left">Parent Phone</th>
 <th class="p-3 text-left">Status</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($students as $student)
 <tr class="border-b hover:bg-gray-50">
 <td class="p-3">{{ $student->admission_no }}</td>
 <td class="p-3">{{ $student->full_name }}</td>
 <td class="p-3">{{ ucfirst($student->level) }}</td>
 <td class="p-3">{{ $student->classroom?->name ?? "-" }}</td>
 <td class="p-3">{{ $student->parent_phone }}</td>
 <td class="p-3">{{ ucfirst($student->status) }}</td>
 <td class="p-3">
 <a href="{{ route("admin.students.show", $student) }}" class="text-blue-700 hover:underline">View</a>
 <a href="{{ route("admin.students.edit", $student) }}" class="text-yellow-700 hover:underline ml-2">Edit</a>
 </td>
 </tr>
 @empty
 <tr><td colspan="7" class="p-6 text-center text-gray-500">No students yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $students->links() }}</div>
@endsection