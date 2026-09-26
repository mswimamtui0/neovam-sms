@extends("layouts.app")
@section("title", "Emergency — " . $emergency->student_name)
@section("content")
<div class="max-w-4xl mx-auto">
    <a href="{{ route("emergencies.index") }}" class="text-blue-700 hover:underline text-sm">Back to Emergencies</a>

    @if(session("success"))
        <div class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 mt-4 mb-4 rounded">{{ session("success") }}</div>
    @endif

    <div class="mt-4 bg-white rounded shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-red-700">{{ $emergency->student_name }}</h1>
                <p class="text-gray-600">{{ $emergency->class_name }} &middot; {{ $emergency->created_at->format("d M Y H:i") }}</p>
            </div>
            <span class="px-3 py-1 rounded text-sm font-semibold
                @if($emergency->severity === "critical") bg-red-100 text-red-800
                @elseif($emergency->severity === "urgent") bg-orange-100 text-orange-800
                @elseif($emergency->severity === "normal") bg-blue-100 text-blue-800
                @else bg-gray-100 text-gray-800 @endif">
                {{ strtoupper($emergency->severity) }}
            </span>
        </div>

        <dl class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <dt class="text-sm text-gray-500">Type</dt>
                <dd class="font-semibold">{{ ucfirst($emergency->type) }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Location</dt>
                <dd class="font-semibold">{{ $emergency->location ?? "-" }}</dd>
            </div>
            <div class="col-span-2">
                <dt class="text-sm text-gray-500">Description</dt>
                <dd class="font-semibold">{{ $emergency->description }}</dd>
            </div>
            <div class="col-span-2">
                <dt class="text-sm text-gray-500">Action Taken</dt>
                <dd class="font-semibold">{{ $emergency->action_taken ?? "-" }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Reported By</dt>
                <dd class="font-semibold">{{ $emergency->recorded_by }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Status</dt>
                <dd class="font-semibold">{{ ucfirst($emergency->status) }}</dd>
            </div>
        </dl>

        <div class="bg-gray-50 rounded p-4 mb-6">
            <h3 class="font-bold text-gray-900 mb-3">Notification Status</h3>
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div>
                    <div class="font-semibold text-gray-700">Headmaster</div>
                    <div class="{{ $emergency->head_notified ? "text-green-700" : "text-red-700" }}">
                        {{ $emergency->head_notified ? "Sent " . $emergency->head_notified_at?->format("H:i") : "Not sent" }}
                    </div>
                </div>
                <div>
                    <div class="font-semibold text-gray-700">Parent</div>
                    <div class="{{ $emergency->parent_notified ? "text-green-700" : "text-red-700" }}">
                        {{ $emergency->parent_notified ? "Sent " . $emergency->parent_notified_at?->format("H:i") : "Not sent" }}
                    </div>
                </div>
                <div>
                    <div class="font-semibold text-gray-700">Health Teacher</div>
                    <div class="{{ $emergency->health_teacher_notified ? "text-green-700" : "text-red-700" }}">
                        {{ $emergency->health_teacher_notified ? "Sent " . $emergency->health_teacher_notified_at?->format("H:i") : "Not sent" }}
                    </div>
                </div>
            </div>
        </div>

        @if($emergency->parent_response || $emergency->follow_up || $emergency->resolved_at)
            <div class="bg-blue-50 rounded p-4 mb-6">
                <h3 class="font-bold text-gray-900 mb-3">Follow-up</h3>
                @if($emergency->parent_response)
                    <div class="mb-2"><strong>Parent response:</strong> {{ $emergency->parent_response }}</div>
                @endif
                @if($emergency->follow_up)
                    <div class="mb-2"><strong>Follow-up:</strong> {{ $emergency->follow_up }}</div>
                @endif
                @if($emergency->resolved_at)
                    <div class="text-green-700 font-semibold">Resolved: {{ $emergency->resolved_at->format("d M Y H:i") }}</div>
                @endif
            </div>
        @endif

        <div class="flex gap-3">
            <a href="{{ route("emergencies.edit", $emergency->id) }}"
               class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Update / Resolve</a>

            <form method="POST" action="{{ route("emergencies.resend", $emergency->id) }}" class="inline">
                @csrf
                <button class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800"
                        onclick="return confirm('Resend SMS to Headmaster, Parent and Health Teacher?');">
                    Resend SMS
                </button>
            </form>

            <form method="POST" action="{{ route("emergencies.destroy", $emergency->id) }}" class="inline"
                  onsubmit="return confirm('Delete this emergency?');">
                @csrf @method("DELETE")
                <button class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection