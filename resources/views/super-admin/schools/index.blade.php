@extends("layouts.app")
@section("title", "All Schools")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">All Schools</h1>
 <a href="{{ route("super-admin.schools.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add School</a>
 </div>

 <div class="grid grid-cols-5 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-xs text-gray-500">Total Schools</div>
 <div class="text-2xl font-bold">{{ $stats["total_schools"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-xs text-gray-500">Active</div>
 <div class="text-2xl font-bold text-green-800">{{ $stats["active_schools"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
 <div class="text-xs text-gray-500">Main Schools</div>
 <div class="text-2xl font-bold text-purple-800">{{ $stats["main_schools"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-xs text-gray-500">Branches</div>
 <div class="text-2xl font-bold text-yellow-800">{{ $stats["branches"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
 <div class="text-xs text-gray-500">Total Users</div>
 <div class="text-2xl font-bold text-red-800">{{ $stats["total_users"] }}</div>
 </div>
 </div>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Code</th>
 <th class="p-3 text-left">Name</th>
 <th class="p-3 text-left">Group</th>
 <th class="p-3 text-left">Parent</th>
 <th class="p-3 text-right">Users</th>
 <th class="p-3 text-right">Students</th>
 <th class="p-3 text-right">Staff</th>
 <th class="p-3 text-left">Status</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($schools as $s)
 <tr class="border-b hover:bg-gray-50">
 <td class="p-3 font-mono text-xs">{{ $s->code }}</td>
 <td class="p-3 font-semibold">{{ $s->name }}</td>
 <td class="p-3 text-xs">{{ $s->group_name ?? "-" }}</td>
 <td class="p-3 text-xs">{{ $s->parent?->name ?? "Main" }}</td>
 <td class="p-3 text-right">{{ $s->users_count }}</td>
 <td class="p-3 text-right">{{ $s->students_count }}</td>
 <td class="p-3 text-right">{{ $s->staff_count }}</td>
 <td class="p-3">
 @if($s->is_active)
 <span class="text-xs px-2 py-0.5 rounded bg-green-100 text-green-800">Active</span>
 @else
 <span class="text-xs px-2 py-0.5 rounded bg-red-100 text-red-800">Inactive</span>
 @endif
 </td>
 <td class="p-3">
 <a href="{{ route("super-admin.schools.show", $s) }}" class="text-blue-700 hover:underline">View</a>
 <a href="{{ route("super-admin.schools.edit", $s) }}" class="text-yellow-700 hover:underline ml-2">Edit</a>
 </td>
 </tr>
 @empty
 <tr><td colspan="9" class="p-6 text-center text-gray-500">No schools.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $schools->links() }}</div>
@endsection