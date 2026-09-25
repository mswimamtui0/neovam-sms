@extends("layouts.app")
@section("title", "Staff")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Staff</h1>
 <div class="flex gap-2">
 <a href="{{ route("admin.subjects.index") }}" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">Manage Subjects</a>
 <a href="{{ route("admin.staff.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">Add Staff</a>
 </div>
 </div>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
 <select name="staff_type" class="border rounded px-3 py-2">
 <option value="">All Types</option>
 @foreach($types as $type => $dept)
 <option value="{{ $type }}" {{ request("staff_type") === $type ? "selected" : "" }}>{{ $type }}</option>
 @endforeach
 </select>
 <select name="department" class="border rounded px-3 py-2">
 <option value="">All Departments</option>
 @foreach(array_unique(array_values($types)) as $dept)
 <option value="{{ $dept }}" {{ request("department") === $dept ? "selected" : "" }}>{{ $dept }}</option>
 @endforeach
 </select>
 <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
 <a href="{{ route("admin.staff.index") }}" class="text-gray-600 px-4 py-2">Reset</a>
 </form>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Staff No</th>
 <th class="p-3 text-left">Name</th>
 <th class="p-3 text-left">Type</th>
 <th class="p-3 text-left">Department</th>
 <th class="p-3 text-left">Phone</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($staff as $member)
 <tr class="border-b hover:bg-gray-50">
 <td class="p-3">{{ $member->staff_no }}</td>
 <td class="p-3">{{ $member->full_name }}</td>
 <td class="p-3">{{ $member->staff_type ?? "-" }}</td>
 <td class="p-3">{{ $member->department }}</td>
 <td class="p-3">{{ $member->phone }}</td>
 <td class="p-3">
 <a href="{{ route("admin.staff.show", $member) }}" class="text-blue-700 hover:underline">View</a>
 <a href="{{ route("admin.staff.edit", $member) }}" class="text-yellow-700 hover:underline ml-2">Edit</a>
 </td>
 </tr>
 @empty
 <tr><td colspan="6" class="p-6 text-center text-gray-500">No staff yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $staff->links() }}</div>
@endsection