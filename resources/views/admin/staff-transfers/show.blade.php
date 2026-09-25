@extends("layouts.app")
@section("title", "Transfer Details")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Transfer #{{ $transfer->id }}</h1>
        <a href="{{ route("admin.staff-transfers.index") }}" class="text-blue-700 hover:underline">← Back</a>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4">
            <h3 class="font-bold text-blue-900 mb-2">Staff</h3>
            <p><strong>Name:</strong> {{ $transfer->staff?->full_name }}</p>
            <p><strong>Staff No:</strong> {{ $transfer->staff?->staff_no }}</p>
            <p><strong>Current Department:</strong> {{ $transfer->staff?->department }}</p>
        </div>
        <div class="bg-white rounded shadow p-4">
            <h3 class="font-bold text-blue-900 mb-2">Transfer</h3>
            <p><strong>Type:</strong> {{ $transfer->typeLabel() }}</p>
            <p><strong>Effective:</strong> {{ $transfer->effective_date?->format("d M Y") }}</p>
            <p>
                <strong>Status:</strong>
                <span class="text-xs px-2 py-0.5 rounded
                    @if($transfer->status === "approved") bg-green-100 text-green-800
                    @elseif($transfer->status === "rejected") bg-red-100 text-red-800
                    @elseif($transfer->status === "pending") bg-yellow-100 text-yellow-800
                    @else bg-blue-100 text-blue-800 @endif">
                    {{ ucfirst($transfer->status) }}
                </span>
            </p>
        </div>
    </div>

    <div class="bg-white rounded shadow p-6 mb-6">
        <h3 class="font-bold text-blue-900 mb-3">Details</h3>
        <table class="w-full text-sm">
            <tr><td class="py-1 font-semibold">From Position</td><td>{{ $transfer->from_position ?? "-" }}</td></tr>
            <tr><td class="py-1 font-semibold">To Position</td><td>{{ $transfer->to_position ?? "-" }}</td></tr>
            <tr><td class="py-1 font-semibold">From Department</td><td>{{ $transfer->from_department ?? "-" }}</td></tr>
            <tr><td class="py-1 font-semibold">To Department</td><td>{{ $transfer->to_department ?? "-" }}</td></tr>
            <tr><td class="py-1 font-semibold">From School</td><td>{{ $transfer->from_school ?? "-" }}</td></tr>
            <tr><td class="py-1 font-semibold">To School</td><td>{{ $transfer->to_school ?? "-" }}</td></tr>
            <tr><td class="py-1 font-semibold">Reason</td><td>{{ $transfer->reason ?? "-" }}</td></tr>
            <tr><td class="py-1 font-semibold">Notes</td><td>{{ $transfer->notes ?? "-" }}</td></tr>
        </table>
    </div>

    @if($transfer->approved_at)
        <div class="bg-gray-50 border-l-4 border-gray-600 p-4 rounded mb-6">
            <p><strong>Approved by:</strong> {{ $transfer->approver?->name }}</p>
            <p><strong>Approved at:</strong> {{ $transfer->approved_at->format("Y-m-d H:i") }}</p>
            @if($transfer->approval_notes)
                <p><strong>Notes:</strong> {{ $transfer->approval_notes }}</p>
            @endif
        </div>
    @endif

    @if($transfer->status === "pending")
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded shadow p-6">
                <h3 class="font-bold text-green-800 mb-3">Approve Transfer</h3>
                <form method="POST" action="{{ route("admin.staff-transfers.approve", $transfer) }}">
                    @csrf
                    <textarea name="approval_notes" rows="3" placeholder="Approval notes (optional)" class="w-full border rounded px-3 py-2 mb-3"></textarea>
                    <label class="flex items-center gap-2 mb-3">
                        <input type="checkbox" name="send_sms" value="1" checked>
                        <span class="text-sm">Send SMS to staff</span>
                    </label>
                    <button class="bg-green-700 text-white px-6 py-2 rounded">Approve</button>
                </form>
            </div>

            <div class="bg-white rounded shadow p-6">
                <h3 class="font-bold text-red-800 mb-3">Reject Transfer</h3>
                <form method="POST" action="{{ route("admin.staff-transfers.reject", $transfer) }}">
                    @csrf
                    <textarea name="approval_notes" rows="3" placeholder="Reason for rejection (required)" class="w-full border rounded px-3 py-2 mb-3" required></textarea>
                    <button class="bg-red-700 text-white px-6 py-2 rounded">Reject</button>
                </form>
            </div>
        </div>
    @endif
@endsection