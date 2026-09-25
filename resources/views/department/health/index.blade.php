@extends("layouts.app")
@section("title", "Health")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-red-900">Health Department</h1>
 <a href="{{ route("department.health.create") }}" class="bg-red-700 text-white px-4 py-2 rounded">Add Health Record</a>
 </div>

 <div class="grid grid-cols-4 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-red-600"><div class="text-xs text-gray-500">Total</div><div class="text-2xl font-bold">{{ $stats["total"] }}</div></div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600"><div class="text-xs text-gray-500">Today</div><div class="text-2xl font-bold">{{ $stats["today"] }}</div></div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-red-800"><div class="text-xs text-gray-500">Severe</div><div class="text-2xl font-bold">{{ $stats["severe"] }}</div></div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-orange-600"><div class="text-xs text-gray-500">Referred</div><div class="text-2xl font-bold">{{ $stats["referred"] }}</div></div>
 </div>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-red-700 text-white"><tr><th class="p-3 text-left">Date</th><th class="p-3 text-left">Student</th><th class="p-3 text-left">Type</th><th class="p-3 text-left">Title</th><th class="p-3 text-left">Severity</th><th class="p-3 text-left">Referred</th></tr></thead>
 <tbody>
 @forelse($records as $r)
 <tr class="border-b">
 <td class="p-3">{{ $r->record_date?->format("Y-m-d") }}</td>
 <td class="p-3">{{ $r->student?->full_name }}</td>
 <td class="p-3">{{ ucfirst(str_replace("_"," ",$r->record_type)) }}</td>
 <td class="p-3">{{ $r->title }}</td>
 <td class="p-3">{{ ucfirst($r->severity) }}</td>
 <td class="p-3">{{ $r->referred_hospital ? "Yes" : "No" }}</td>
 </tr>
 @empty
 <tr><td colspan="6" class="p-6 text-center text-gray-500">No records.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $records->links() }}</div>
@endsection