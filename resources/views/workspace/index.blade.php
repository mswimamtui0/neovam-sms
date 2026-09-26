@extends("layouts.app")
@section("title", "My Workspace")
@section("content")
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-900">My Workspace</h1>
        <p class="text-gray-600 mt-1">
            {{ $staff->full_name }}
            @if($staff->primaryDepartment) &middot; {{ $staff->primaryDepartment->name }} @endif
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded shadow p-6">
            <div class="text-2xl font-bold text-blue-900">{{ $subjects->count() }}</div>
            <div class="text-sm text-gray-500 mt-1">Subjects Taught</div>
        </div>
        <div class="bg-white rounded shadow p-6">
            <div class="text-2xl font-bold text-blue-900">{{ $assignedClasses->count() }}</div>
            <div class="text-sm text-gray-500 mt-1">Classes Assigned</div>
        </div>
        <div class="bg-white rounded shadow p-6">
            <div class="text-2xl font-bold text-blue-900">{{ $classTeacherOf ? $classTeacherOf->name : "-" }}</div>
            <div class="text-sm text-gray-500 mt-1">Class Teacher Of</div>
        </div>
    </div>

    @if($subjects->count())
        <h2 class="text-xl font-bold text-blue-900 mb-4">My Subjects</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            @foreach($subjects as $subject)
                <div class="bg-white rounded shadow p-4">
                    <div class="font-semibold">{{ $subject->name }}</div>
                    <div class="text-xs text-gray-500">{{ $subject->code }}</div>
                </div>
            @endforeach
        </div>
    @endif

    @if($assignedClasses->count())
        <h2 class="text-xl font-bold text-blue-900 mb-4">My Classes</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($assignedClasses as $room)
                <div class="bg-white rounded shadow p-4">
                    <div class="font-semibold">{{ $room->name }}</div>
                    <div class="text-xs text-gray-500">{{ $room->level }}</div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-10 bg-white rounded shadow p-6">
        <a href="{{ route("hub.index") }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded">Back to My Hub</a>
    </div>
</div>
@endsection