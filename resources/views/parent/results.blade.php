@extends("layouts.app")
@section("title", "Children Results")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Children Results</h1>
    @foreach($children as $child)
        <div class="bg-white rounded shadow p-4 mb-4">
            <h2 class="font-bold text-blue-900">{{ $child->full_name }}</h2>
            @forelse($child->results as $r)
                <div class="text-sm border-b py-2">{{ $r->subject }} - {{ $r->marks }} ({{ $r->grade }})</div>
            @empty
                <p class="text-gray-500 text-sm">No results.</p>
            @endforelse
        </div>
    @endforeach
@endsection