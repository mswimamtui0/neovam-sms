@extends("layouts.app")
@section("title", "Add to Environment")
@section("content")
 <h1 class="text-3xl font-bold text-teal-900 mb-6">Add Environment Record</h1>
 <form method="POST" action="{{ route('department.environment.store') }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 @include('department.environment._form')
 <button type="submit" class="bg-teal-700 text-white px-6 py-2 rounded">Save</button>
 </form>
@endsection