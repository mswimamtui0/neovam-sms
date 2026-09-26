@extends("layouts.app")
@section("title", $title)
@section("content")
<div class="max-w-6xl mx-auto">
    <a href="{{ route("hub.index") }}" class="text-blue-700 hover:underline text-sm">Back to Hub</a>
    <h1 class="text-3xl font-bold text-blue-900 mt-2 mb-6">{{ $title }}</h1>

    @if(!empty($stats))
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @foreach($stats as $label => $value)
                <div class="bg-white rounded shadow p-6">
                    <div class="text-2xl font-bold text-blue-900">{{ $value }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ $label }}</div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="bg-white rounded shadow p-6">
        <p class="text-gray-600">This is your <strong>{{ $title }}</strong> workspace. Records will appear here as they are added.</p>
    </div>
</div>
@endsection