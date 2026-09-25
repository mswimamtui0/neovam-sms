@extends("layouts.app")
@section("title", "Super Admin Dashboard")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-2">Super Admin Dashboard</h1>
 <p class="text-gray-600 mb-6">Overview of all schools in the NEOVAM network</p>

 <div class="grid grid-cols-4 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-xs text-gray-500">Total Students</div>
 <div class="text-3xl font-bold text-blue-900">{{ number_format($totalStudents) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-xs text-gray-500">Total Staff</div>
 <div class="text-3xl font-bold text-green-900">{{ number_format($totalStaff) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-xs text-gray-500">Total Revenue (TZS)</div>
 <div class="text-3xl font-bold text-yellow-900">{{ number_format($totalRevenue) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
 <div class="text-xs text-gray-500">SMS Units Left</div>
 <div class="text-3xl font-bold text-purple-900">{{ number_format($totalSmsUnits) }}</div>
 </div>
 </div>

 <div class="flex justify-between items-center mb-3">
 <h2 class="text-xl font-bold text-blue-900">All Schools</h2>
 <div class="flex gap-2">
 <a href="{{ route("super-admin.schools.compare") }}" class="bg-gray-700 text-white px-4 py-2 rounded text-sm">Compare</a>
 <a href="{{ route("super-admin.schools.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded text-sm">Add School</a>
 </div>
 </div>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">School</th>
 <th class="p-3 text-left">Group / Parent</th>
 <th class="p-3 text-right">Students</th>
 <th class="p-3 text-right">Staff</th>
 <th class="p-3 text-right">SMS Units</th>
 <th class="p-3 text-left">Status</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($schools as $s)
 <tr class="border-b">
 <td class="p-3 font-semibold">{{ $s->name }}</td>
 <td class="p-3 text-xs">{{ $s->group_name ?? $s->parent?->name ?? "-" }}</td>
 <td class="p-3 text-right">{{ $s->students_count }}</td>
 <td class="p-3 text-right">{{ $s->staff_count }}</td>
 <td class="p-3 text-right">{{ number_format($s->sms_balance_units) }}</td>
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
 <tr><td colspan="7" class="p-6 text-center text-gray-500">No schools.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
@endsection