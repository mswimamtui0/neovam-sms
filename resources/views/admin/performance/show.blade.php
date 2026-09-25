@extends("layouts.app")
@section("title", "Review Report")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-blue-900">Review Report</h1>
            <p class="text-gray-600">{{ $report->staff?->full_name }} — {{ ucfirst($report->report_type) }} Report</p>
        </div>
        <a href="{{ route("admin.performance-reviews.index") }}" class="text-blue-700 hover:underline">← Back</a>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">

        {{-- LEFT: Report Content --}}
        <div class="space-y-4">
            <div class="bg-white rounded shadow p-4">
                <h3 class="font-bold text-blue-900 mb-2">Report Period</h3>
                <p><strong>From:</strong> {{ $report->week_start?->format("d M Y") }}</p>
                <p><strong>To:</strong> {{ $report->week_end?->format("d M Y") }}</p>
                <p><strong>Submitted:</strong> {{ $report->created_at->format("d M Y H:i") }}</p>
            </div>

            <div class="bg-white rounded shadow p-4">
                <h3 class="font-bold text-blue-900 mb-2">Stats</h3>
                <p><strong>Periods Taught:</strong> {{ $report->periods_taught }}</p>
                <p><strong>Students Absent:</strong> {{ $report->students_absent }}</p>
            </div>

            <div class="bg-white rounded shadow p-4">
                <h3 class="font-bold text-blue-900 mb-2">Summary</h3>
                <p class="whitespace-pre-line text-sm">{{ $report->summary }}</p>
            </div>

            @if($report->challenges)
                <div class="bg-white rounded shadow p-4">
                    <h3 class="font-bold text-blue-900 mb-2">Challenges</h3>
                    <p class="whitespace-pre-line text-sm">{{ $report->challenges }}</p>
                </div>
            @endif

            @if($report->next_plan)
                <div class="bg-white rounded shadow p-4">
                    <h3 class="font-bold text-blue-900 mb-2">Next Week Plan</h3>
                    <p class="whitespace-pre-line text-sm">{{ $report->next_plan }}</p>
                </div>
            @endif
        </div>

        {{-- RIGHT: Review Actions --}}
        <div class="space-y-4">

            {{-- Existing review --}}
            @if($report->reviewed_at)
                <div class="bg-gray-50 border-l-4 border-gray-600 p-4 rounded">
                    <h3 class="font-bold mb-2">Previous Review</h3>
                    <p><strong>Status:</strong> {{ ucfirst(str_replace("_"," ",$report->review_status)) }}</p>
                    <p><strong>Rating:</strong> {{ $report->rating ? $report->rating . "/5" : "-" }}</p>
                    <p><strong>By:</strong> {{ $report->reviewer?->name ?? "-" }}</p>
                    <p><strong>At:</strong> {{ $report->reviewed_at->format("Y-m-d H:i") }}</p>
                    @if($report->head_comment)
                        <p class="mt-2 text-sm"><strong>Comment:</strong> {{ $report->head_comment }}</p>
                    @endif
                </div>
            @endif

            {{-- Approve --}}
            <div class="bg-white rounded shadow p-4">
                <h3 class="font-bold text-green-800 mb-3">Approve</h3>
                <form method="POST" action="{{ route("admin.performance-reviews.approve", $report) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block font-semibold text-sm mb-1">Rating</label>
                        <select name="rating" class="w-full border rounded px-3 py-2" required>
                            @for($i=5; $i>=1; $i--)
                                <option value="{{ $i }}">{{ $i }} star{{ $i > 1 ? "s" : "" }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-sm mb-1">Head Comment</label>
                        <textarea name="head_comment" rows="3" class="w-full border rounded px-3 py-2"></textarea>
                    </div>
                    <button class="bg-green-700 text-white px-6 py-2 rounded w-full">Approve</button>
                </form>
            </div>

            {{-- Request Revision --}}
            <div class="bg-white rounded shadow p-4">
                <h3 class="font-bold text-orange-800 mb-3">Request Revision</h3>
                <form method="POST" action="{{ route("admin.performance-reviews.revision", $report) }}" class="space-y-3">
                    @csrf
                    <textarea name="head_comment" rows="3" placeholder="What needs revising?" class="w-full border rounded px-3 py-2" required></textarea>
                    <button class="bg-orange-700 text-white px-6 py-2 rounded w-full">Request Revision</button>
                </form>
            </div>

            {{-- Reject --}}
            <div class="bg-white rounded shadow p-4">
                <h3 class="font-bold text-red-800 mb-3">Reject</h3>
                <form method="POST" action="{{ route("admin.performance-reviews.reject", $report) }}" class="space-y-3">
                    @csrf
                    <textarea name="head_comment" rows="3" placeholder="Reason for rejection (required)" class="w-full border rounded px-3 py-2" required></textarea>
                    <button class="bg-red-700 text-white px-6 py-2 rounded w-full">Reject</button>
                </form>
            </div>

        </div>
    </div>
@endsection