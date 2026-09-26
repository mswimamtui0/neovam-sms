@extends("layouts.app")
@section("title", $department->name . " Department")
@section("content")
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <a href="{{ route("hub.index") }}" class="text-blue-700 hover:underline text-sm">Back to Hub</a>
        <h1 class="text-3xl font-bold text-blue-900 mt-2">{{ $department->name }} Department</h1>
        <p class="text-gray-600 mt-1">{{ $department->description }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @foreach($stats as $label => $value)
            <div class="bg-white rounded shadow p-6">
                <div class="text-2xl font-bold text-blue-900">{{ $value }}</div>
                <div class="text-sm text-gray-500 mt-1">{{ $label }}</div>
            </div>
        @endforeach
    </div>

    @if($subjects->count())
        <h2 class="text-xl font-bold text-blue-900 mb-4">Subjects in this Department</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($subjects as $subject)
                <div class="bg-white rounded shadow p-4">
                    <div class="font-semibold">{{ $subject->name }}</div>
                    <div class="text-xs text-gray-500">{{ $subject->code }}</div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection