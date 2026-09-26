@extends("layouts.app")
@section("title", ($record ? "Edit" : "Add") . " Record - " . $department->name)
@section("content")
    <div class="mb-6">
        <a href="{{ route("dept." . $department->code . ".index") }}" class="text-blue-700 hover:underline text-sm">Back to {{ $department->name }}</a>
        <h1 class="text-3xl font-bold text-blue-900 mt-2">{{ $record ? "Edit" : "Add" }} Record</h1>
    </div>

    @include("departments._shared.form")
@endsection