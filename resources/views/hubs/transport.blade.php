@extends("layouts.app")
@section("title", "Transport Hub")
@section("content")
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Transport Hub</h1>
        <p class="text-gray-600 mt-1">Trips, fuel logs, bus maintenance and gate passes in one place.</p>
    </div>

    @include("hubs._summary")
    @include("hubs._tabs")
</div>
@endsection