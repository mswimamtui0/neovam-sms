@extends("layouts.app")
@section("title", $department->name . " Department")
@section("content")
    @include("departments._shared.header")
    {{-- Emergency Quick Access --}}
    <div class="bg-red-50 border-l-4 border-red-600 p-4 mb-6 rounded flex justify-between items-center">
        <div>
            <div class="font-bold text-red-800">Emergency Alert</div>
            <div class="text-sm text-red-700">Send SMS to Headmaster, Parent and Health Teacher instantly.</div>
        </div>
        <a href="{{ route("emergencies.create") }}"
           class="bg-red-700 text-white px-5 py-2 rounded font-bold hover:bg-red-800">
            Log Emergency
        </a>
    </div>


    @if(session("success"))
        <div class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 mb-4 rounded">{{ session("success") }}</div>
    @endif
    @if(session("error"))
        <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 mb-4 rounded">{{ session("error") }}</div>
    @endif

    <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
        <input type="text" name="q" value="{{ request("q") }}" placeholder="Search..." class="border rounded px-3 py-2 flex-1">
        <button class="bg-blue-900 text-white px-4 py-2 rounded">Search</button>
        <a href="{{ route("dept." . $department->code . ".index") }}" class="text-gray-600 px-4 py-2">Reset</a>
    </form>

    @include("departments._shared.table")
@endsection