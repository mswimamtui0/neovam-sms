@extends("layouts.app")
@section("title", "Boarding Hub")
@section("content")
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Boarding Hub</h1>
        <p class="text-gray-600 mt-1">Everything about boarding life in one place: roll call, meals, health, discipline, visitors, laundry, incidents.</p>
    </div>

    @include("hubs._summary")
    @include("hubs._tabs")
</div>
@endsection