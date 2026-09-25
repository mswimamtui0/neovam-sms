@extends("layouts.app")
@section("title", "Performance Reviews")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Weekly Performance Reviews</h1>
 <a href="{{ route("admin.performance-reviews.dashboard") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Dashboard</a>
 </div>

 <div class="grid grid-cols-5 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-xs text-gray-500">Pending</div>
 <div class="text-2xl font-bold text-yellow-800">{{ $counts["pending"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-xs text-gray-500">Under Review</div>
 <div class="text-2xl font-bold text-blue-800">{{ $counts["under_review"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-xs text-gray-500">Approved</div>
 <div class="text-2xl font-bold text-green-800">{{ $counts["approved"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
 <div class="text-xs text-gray-500">Rejected</div>
 <div class="text-2xl font-bold text-red-800">{{ $counts["rejected"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-orange-600">
 <div class="text-xs text-gray-500">Revision</div>
 <div class="text-2xl font-bold text-orange-800">{{ $counts["needs_revision"] }}</div>
 </div>
 </div>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex flex-wrap gap-3">
 <select name="review_status" class="border rounded px-3 py-2">
 <option value="">All Review Status</option>
 @foreach(["pending","under_review","approved","rejected","needs_revision"] as $s)
 <option value="{{ $s }}" {{ request("review_status") === $s ? "selected" : "" }}>{{ ucfirst(str_replace("_"," ",$s)) }}</option>
 @endforeach
 </select>
 <select name="staff_id" class="border rounded px-3 py-2">
 <option value="">All Staff</option>
 @foreach($staffList as $s)
 <option value="{{ $s->id }}" {{ request("staff_id") == $s->id ? "selected" : "" }}>{{ $s->full_name }}</option>
 @endforeach
 </select>
 <select name="report_type" class="border rounded px-3 py-2">
 <option value="">All Types</option>
 @foreach(["daily","weekly","monthly","termly"] as $t)
 <option value="{{ $t }}" {{ request("report_type") === $t ? "selected" : "" }}>{{ ucfirst($t) }}</option>
 @endforeach
 </select>
 <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
 <a href="{{ route("admin.performance-reviews.index") }}" class="text-gray-600 px-4 py-2">Reset</a>
 </form>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Teacher</th>
 <th class="p-3 text-left">Type</th>
 <th class="p-3 text-left">Period</th>
 <th class="p-3 text-left">Periods</th>
 <th class="p-3 text-left">Rating</th>
 <th class="p-3 text-left">Review</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($reports as $r)
 <tr class="border-b hover:bg-gray-50">
 <td class="p-3 font-semibold">{{ $r->staff?->full_name ?? "-" }}</td>
 <td class="p-3">{{ ucfirst($r->report_type) }}</td>
 <td class="p-3 text-xs">
 {{ $r->week_start?->format("d M") }} {{ $r->week_end?->format("d M Y") }}
 </td>
 <td class="p-3">{{ $r->periods_taught }}</td>
 <td class="p-3">
 @if($r->rating)
 <span class="text-yellow-600 font-bold">{{ $r->rating }}/5</span>
 @else - @endif
 </td>
 <td class="p-3">
 <span class="text-xs px-2 py-0.5 rounded
 @if($r->review_status === "approved") bg-green-100 text-green-800
 @elseif($r->review_status === "rejected") bg-red-100 text-red-800
 @elseif($r->review_status === "needs_revision") bg-orange-100 text-orange-800
 @elseif($r->review_status === "under_review") bg-blue-100 text-blue-800
 @else bg-gray-200 text-gray-800 @endif">
 {{ ucfirst(str_replace("_"," ",$r->review_status)) }}
 </span>
 </td>
 <td class="p-3">
 <a href="{{ route("admin.performance-reviews.show", $r) }}" class="text-blue-700 hover:underline">Review</a>
 </td>
 </tr>
 @empty
 <tr><td colspan="7" class="p-6 text-center text-gray-500">No reports yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $reports->links() }}</div>
@endsection