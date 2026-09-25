@extends("layouts.app")
@section("title", "Add to Feeding")
@section("content")
 <h1 class="text-3xl font-bold text-orange-900 mb-6">Add Feeding Record</h1>
 <form method="POST" action="{{ route('department.feeding.store') }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 @include('department.feeding._form')
 <button type="submit" class="bg-orange-700 text-white px-6 py-2 rounded">Save</button>
 </form>
@endsection