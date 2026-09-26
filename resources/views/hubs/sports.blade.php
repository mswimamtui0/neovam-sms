@extends("layouts.app")
@section("title", "Sports Hub")
@section("content")
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Sports Hub</h1>
        <p class="text-gray-600 mt-1">Matches, trainings, injuries, team trips and uniform orders in one place.</p>
    </div>

    @include("hubs._summary")
    @include("hubs._tabs")
</div>
@endsection