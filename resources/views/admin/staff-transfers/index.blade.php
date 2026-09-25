@extends("layouts.app")
@section("title", "Staff Transfers")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Staff Transfers</h1>
        <a href="{{ route("admin.staff-transfers.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">New Transfer</a>
    </div>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
            <div class="text-xs text-gray-500">Pending</div>
            <div class="text-2xl font-bold text-yellow-800">{{ $counts["pending"] }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
            <div class="text-xs text-gray-500">Approved</div>
            <div class="text-2xl font-bold text-green-800">{{ $counts["approved"] }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
            <div class="text-xs text-gray-500">Rejected</div>
            <div class="text-2xl font-bold text-red-800">{{ $counts["rejected"] }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
            <div class="text-xs text-gray-500">Completed</div>
            <div class="text-2xl font-bold text-blue-800">{{ $counts["completed"] }}</div>
        </div>
    </div>

    <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
        <select name="status" class="border rounded px-3 py-2">
            <option value="">All Statuses</option>
            @foreach(["pending","approved","rejected","completed"] as $s)
                <option value="{{ $s }}" {{ request("status") === $s ? "selected" : "" }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <select name="type" class="border rounded px-3 py-2">
            <option value="">All Types</option>
            @foreach(["transfer_in","transfer_out","internal_move","promotion","demotion"] as $t)
                <option value="{{ $t }}" {{ request("type") === $t ? "selected" : "" }}>{{ ucfirst(str_replace("_"," ",$t)) }}</option>
            @endforeach
        </select>
        <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route("admin.staff-transfers.index") }}" class="text-gray-600 px-4 py-2">Reset</a>
    </form>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Staff</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">From → To</th>
                    <th class="p-3 text-left">Effective</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transfers as $t)
                    <tr class="border-b">
                        <td class="p-3 font-semibold">{{ $t->staff?->full_name ?? "-" }}</td>
                        <td class="p-3">{{ $t->typeLabel() }}</td>
                        <td class="p-3 text-xs">
                            {{ $t->from_position ?? "-" }} → {{ $t->to_position ?? "-" }}
                            @if($t->to_department && $t->from_department !== $t->to_department)
                                <br>{{ $t->from_department }} → {{ $t->to_department }}
                            @endif
                        </td>
                        <td class="p-3">{{ $t->effective_date?->format("d M Y") }}</td>
                        <td class="p-3">
                            <span class="text-xs px-2 py-0.5 rounded
                                @if($t->status === "approved") bg-green-100 text-green-800
                                @elseif($t->status === "rejected") bg-red-100 text-red-800
                                @elseif($t->status === "pending") bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($t->status) }}
                            </span>
                        </td>
                        <td class="p-3">
                            <a href="{{ route("admin.staff-transfers.show", $t) }}" class="text-blue-700 hover:underline">View</a>
                            @if($t->status === "pending")
                                <a href="{{ route("admin.staff-transfers.edit", $t) }}" class="text-yellow-700 hover:underline ml-2">Edit</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-6 text-center text-gray-500">No transfers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $transfers->links() }}</div>
@endsection