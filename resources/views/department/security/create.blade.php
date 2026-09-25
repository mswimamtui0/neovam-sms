@extends("layouts.app")
@section("title", "Add to Security")
@section("content")
 <h1 class="text-3xl font-bold text-gray-900 mb-6">Add Security Record</h1>
 <form method="POST" action="{{ route('department.security.store') }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 @include('department.security._form')
 <button type="submit" class="bg-gray-700 text-white px-6 py-2 rounded">Save</button>
 </form>
@endsection