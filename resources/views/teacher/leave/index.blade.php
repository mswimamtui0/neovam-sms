@extends("layouts.app")
@section("title", "Leave Requests")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Leave Requests</h1>
        <a href="{{ route("teacher.leave.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Apply for Leave</a>
    </div>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">From</th>
                    <th class="p-3 text-left">To</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $l)
                    <tr class="border-b">
                        <td class="p-3">{{ ucfirst($l->leave_type) }}</td>
                        <td class="p-3">{{ $l->start_date->format("Y-m-d") }}</td>
                        <td class="p-3">{{ $l->end_date->format("Y-m-d") }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs
                                @if($l->status === "approved") bg-green-100 text-green-800
                                @elseif($l->status === "rejected") bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($l->status) }}
                            </span>
                        </td>
                        <td class="p-3">{{ $l->admin_remarks ?? "-" }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-gray-500">No leave requests.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $leaves->links() }}</div>
@endsection