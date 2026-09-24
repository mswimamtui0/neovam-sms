@extends("layouts.app")
@section("title", "Parent Dashboard")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Parent Dashboard</h1>

    @if($children->isEmpty())
        <div class="bg-white rounded shadow p-6 text-gray-500">
            No children linked to your phone number ({{ auth()->user()->phone }}). Contact the school.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($children as $child)
                <div class="bg-white rounded shadow p-6">
                    <h2 class="text-xl font-bold text-blue-900">{{ $child->full_name }}</h2>
                    <p class="text-gray-600">{{ $child->classroom?->name ?? "-" }} - {{ ucfirst($child->level) }}</p>
                    <p class="text-gray-600">Adm: {{ $child->admission_no }}</p>
                    <a href="{{ route("parent.child", $child) }}" class="inline-block mt-3 bg-blue-900 text-white px-4 py-2 rounded">View Details</a>
                </div>
            @endforeach
        </div>
    @endif
@endsection