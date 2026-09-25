@extends("layouts.app")
@section("title", "Add to Boarding")
@section("content")
 <h1 class="text-3xl font-bold text-purple-900 mb-6">Add Boarding Record</h1>
 <form method="POST" action="{{ route('department.boarding.store') }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 @include('department.boarding._form')
 <button type="submit" class="bg-purple-700 text-white px-6 py-2 rounded">Save</button>
 </form>
@endsection