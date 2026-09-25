@extends("layouts.app")
@section("title", "Duty Reports")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Duty Reports</h1>
 <a href="{{ route("duty.reports.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">New Report</a>
 </div>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">Type</th>
 <th class="p-3 text-left">Incidents</th>
 <th class="p-3 text-left">Status</th>
 </tr>
 </thead>
 <tbody>
 @forelse($reports as $r)
 <tr class="border-b">
 <td class="p-3">{{ $r->report_date->format("Y-m-d") }}</td>
 <td class="p-3">{{ ucfirst($r->report_type) }}</td>
 <td class="p-3">{{ $r->incidents_count }}</td>
 <td class="p-3">{{ ucfirst($r->status) }}</td>
 </tr>
 @empty
 <tr><td colspan="4" class="p-6 text-center text-gray-500">No reports yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $reports->links() }}</div>
@endsection