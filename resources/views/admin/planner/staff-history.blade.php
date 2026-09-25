@extends("layouts.app")
@section("title", "Staff Department History")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">{{ $staff->full_name }} — Department History</h1>
 <a href="{{ route("admin.staff.index") }}" class="text-blue-700 hover:underline">Back</a>
 </div>

 <div class="grid grid-cols-2 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4">
 <div class="text-xs text-gray-500">Current Department</div>
 <div class="text-xl font-bold">{{ $staff->department }}</div>
 </div>
 <div class="bg-white rounded shadow p-4">
 <div class="text-xs text-gray-500">Current Roles</div>
 <div class="text-sm font-mono">{{ $staff->department_roles ?? "None" }}</div>
 </div>
 </div>

 {{-- Change form --}}
 <div class="bg-white rounded shadow p-6 mb-6">
 <h2 class="font-bold text-blue-900 mb-3">Change Department</h2>
 <form method="POST" action="{{ route("admin.planner.staff-history.store", $staff) }}" class="grid grid-cols-2 gap-4">
 @csrf
 <div>
 <label class="block font-semibold mb-1 text-sm">New Roles (comma-separated)</label>
 <input type="text" name="to_roles" value="{{ $staff->department_roles }}" placeholder="health,class_teacher" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1 text-sm">New Department</label>
 <input type="text" name="to_department" value="{{ $staff->department }}" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1 text-sm">Effective Date</label>
 <input type="date" name="effective_date" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1 text-sm">Reason</label>
 <input type="text" name="reason" class="w-full border rounded px-3 py-2">
 </div>
 <button type="submit" class="bg-blue-900 text-white rounded px-4 py-2 col-span-2">Update and Log</button>
 </form>
 </div>

 <h2 class="text-xl font-bold text-blue-900 mb-3">History</h2>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">From Roles</th>
 <th class="p-3 text-left">To Roles</th>
 <th class="p-3 text-left">From Dept</th>
 <th class="p-3 text-left">To Dept</th>
 <th class="p-3 text-left">Reason</th>
 <th class="p-3 text-left">By</th>
 </tr>
 </thead>
 <tbody>
 @forelse($history as $h)
 <tr class="border-b">
 <td class="p-3">{{ $h->effective_date?->format("d M Y") }}</td>
 <td class="p-3 text-xs font-mono">{{ $h->from_roles ?? "-" }}</td>
 <td class="p-3 text-xs font-mono">{{ $h->to_roles ?? "-" }}</td>
 <td class="p-3">{{ $h->from_department ?? "-" }}</td>
 <td class="p-3">{{ $h->to_department ?? "-" }}</td>
 <td class="p-3 text-xs">{{ $h->reason ?? "-" }}</td>
 <td class="p-3 text-xs">{{ $h->changer?->name ?? "-" }}</td>
 </tr>
 @empty
 <tr><td colspan="7" class="p-6 text-center text-gray-500">No history yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
@endsection