@extends("layouts.app")
@section("title", "My Reports")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">My Reports</h1>
 <a href="{{ route("teacher.reports.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Submit Report</a>
 </div>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Type</th>
 <th class="p-3 text-left">Period</th>
 <th class="p-3 text-left">Status</th>
 <th class="p-3 text-left">Submitted</th>
 </tr>
 </thead>
 <tbody>
 @forelse($reports as $r)
 <tr class="border-b">
 <td class="p-3">{{ ucfirst($r->report_type) }}</td>
 <td class="p-3">{{ $r->week_start->format("Y-m-d") }} to {{ $r->week_end->format("Y-m-d") }}</td>
 <td class="p-3">{{ ucfirst($r->status) }}</td>
 <td class="p-3">{{ $r->created_at->format("Y-m-d H:i") }}</td>
 </tr>
 @empty
 <tr><td colspan="4" class="p-6 text-center text-gray-500">No reports yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $reports->links() }}</div>
@endsection