@extends("layouts.app")
@section("title", "Announcements")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">School Announcements</h1>
    @forelse($announcements as $a)
        <div class="bg-white rounded shadow p-6 mb-4 {{ $a->is_pinned ? "border-l-4 border-yellow-600" : "" }}">
            <div class="flex justify-between items-start mb-2">
                <h2 class="text-xl font-bold text-blue-900">{{ $a->title }}</h2>
                @if($a->is_pinned)<span class="text-xs bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded">Pinned</span>@endif
            </div>
            <p class="text-gray-700 whitespace-pre-line">{{ $a->body }}</p>
            <div class="text-xs text-gray-400 mt-3">
                Published: {{ $a->publish_date?->format("Y-m-d") }}
            </div>
        </div>
    @empty
        <div class="bg-white rounded shadow p-6 text-gray-500">No announcements.</div>
    @endforelse
    <div class="mt-4">{{ $announcements->links() }}</div>
@endsection