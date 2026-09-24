@extends("layouts.app")
@section("title", "Emergencies")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-red-900">Emergency / Incidents</h1>
 <a href="{{ route("admin.emergencies.create") }}" class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800">+ Report Incident</a>
 </div>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-red-700 text-white">
 <tr>
 <th class="p-3 text-left">Date</th>
 <th class="p-3 text-left">Student</th>
 <th class="p-3 text-left">Type</th>
 <th class="p-3 text-left">Reported By</th>
 <th class="p-3 text-left">SMS Sent</th>
 </tr>
 </thead>
 <tbody>
 @forelse($incidents as $incident)
 <tr class="border-b hover:bg-gray-50">
 <td class="p-3">{{ $incident->created_at->format("Y-m-d H:i") }}</td>
 <td class="p-3">{{ $incident->student?->full_name ?? "-" }}</td>
 <td class="p-3">{{ $incident->type }}</td>
 <td class="p-3">{{ $incident->reported_by }}</td>
 <td class="p-3">{{ $incident->sms_sent ? "Yes" : "No" }}</td>
 </tr>
 @empty
 <tr><td colspan="5" class="p-6 text-center text-gray-500">No incidents yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $incidents->links() }}</div>
@endsection