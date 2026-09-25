@extends("layouts.app")
@section("title", "Activity Details")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">{{ $activity->title }}</h1>
        <a href="{{ route("admin.department-activities.index") }}" class="text-blue-700 hover:underline">← Back</a>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-gray-500">Department</div>
            <div class="text-xl font-bold">{{ $activity->department }}</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-gray-500">Type</div>
            <div class="text-xl font-bold">{{ ucfirst($activity->activity_type) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-gray-500">Date</div>
            <div class="text-xl font-bold">{{ $activity->activity_date->format("d M Y") }}</div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-gray-500">Status</div>
            <div class="text-lg font-bold">{{ ucfirst(str_replace("_"," ",$activity->status)) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-gray-500">Priority</div>
            <div class="text-lg font-bold">{{ ucfirst($activity->priority) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-gray-500">Assigned To</div>
            <div class="text-lg font-bold">{{ $activity->staff?->full_name ?? "—" }}</div>
        </div>
    </div>

    <div class="bg-white rounded shadow p-6 mb-4">
        <h3 class="font-bold text-blue-900 mb-2">Description</h3>
        <p class="whitespace-pre-line text-sm">{{ $activity->description }}</p>
    </div>

    @if($activity->outcome)
        <div class="bg-white rounded shadow p-6 mb-4">
            <h3 class="font-bold text-blue-900 mb-2">Outcome</h3>
            <p class="whitespace-pre-line text-sm">{{ $activity->outcome }}</p>
        </div>
    @endif

    @if($activity->notes)
        <div class="bg-white rounded shadow p-6 mb-4">
            <h3 class="font-bold text-blue-900 mb-2">Notes</h3>
            <p class="whitespace-pre-line text-sm">{{ $activity->notes }}</p>
        </div>
    @endif

    @if($activity->approved_at)
        <div class="bg-green-50 border-l-4 border-green-600 p-4 rounded">
            <p><strong>Approved by:</strong> {{ $activity->approver?->name }}</p>
            <p><strong>Approved at:</strong> {{ $activity->approved_at->format("Y-m-d H:i") }}</p>
        </div>
    @else
        <form method="POST" action="{{ route("admin.department-activities.approve", $activity) }}">
            @csrf
            <button class="bg-green-700 text-white px-6 py-2 rounded">Acknowledge / Approve</button>
        </form>
    @endif
@endsection