@extends("layouts.app")
@section("title", "Emergency Alerts")
@section("content")
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-red-700">Emergency Alerts</h1>
            <p class="text-gray-600 mt-1">Every emergency notifies Headmaster, Parent and Health Teacher.</p>
        </div>
        <a href="{{ route("emergencies.create") }}"
           class="bg-red-700 text-white px-6 py-3 rounded font-bold hover:bg-red-800">
            Log Emergency
        </a>
    </div>

    @if(session("success"))
        <div class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 mb-4 rounded">{{ session("success") }}</div>
    @endif

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4">
            <div class="text-2xl font-bold text-gray-900">{{ $counts["today"] }}</div>
            <div class="text-xs text-gray-500">Today</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-2xl font-bold text-red-700">{{ $counts["critical"] }}</div>
            <div class="text-xs text-gray-500">Critical today</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-2xl font-bold text-orange-700">{{ $counts["urgent"] }}</div>
            <div class="text-xs text-gray-500">Urgent today</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-2xl font-bold text-blue-700">{{ $counts["open"] }}</div>
            <div class="text-xs text-gray-500">Open cases</div>
        </div>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-red-700 text-white">
                <tr>
                    <th class="p-3 text-left">Time</th>
                    <th class="p-3 text-left">Student</th>
                    <th class="p-3 text-left">Severity</th>
                    <th class="p-3 text-left">Issue</th>
                    <th class="p-3 text-left">Head</th>
                    <th class="p-3 text-left">Parent</th>
                    <th class="p-3 text-left">Health T.</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($emergencies as $e)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $e->created_at->format("d M H:i") }}</td>
                        <td class="p-3 font-semibold">
                            {{ $e->student_name }}
                            <div class="text-xs text-gray-500">{{ $e->class_name }}</div>
                        </td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded text-xs
                                @if($e->severity === "critical") bg-red-100 text-red-800
                                @elseif($e->severity === "urgent") bg-orange-100 text-orange-800
                                @elseif($e->severity === "normal") bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($e->severity) }}
                            </span>
                        </td>
                        <td class="p-3">{{ Str::limit($e->description, 40) }}</td>
                        <td class="p-3">{{ $e->head_notified ? "Yes" : "No" }}</td>
                        <td class="p-3">{{ $e->parent_notified ? "Yes" : "No" }}</td>
                        <td class="p-3">{{ $e->health_teacher_notified ? "Yes" : "No" }}</td>
                        <td class="p-3">{{ ucfirst($e->status) }}</td>
                        <td class="p-3">
                            <a href="{{ route("emergencies.show", $e->id) }}" class="text-blue-700 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="p-6 text-center text-gray-500">No emergencies logged.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $emergencies->links() }}</div>
</div>
@endsection