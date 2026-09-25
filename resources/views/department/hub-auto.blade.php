@extends("layouts.app")
@section("title", "My Department")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-2">Welcome, {{ $staff?->full_name ?? auth()->user()->name }}</h1>
 <p class="text-gray-600 mb-6">
 @if($staff && $staff->hasAnyDepartmentRole())
 You are assigned to <strong>{{ count($cards) }}</strong>department{{ count($cards) > 1 ? "s" : "" }}.
 Choose a department below to start working.
 @else
 No department assigned yet. Contact admin.
 @endif
 </p>

 @if($staff && $staff->hasAnyDepartmentRole())
 <div class="grid grid-cols-3 gap-6">
 @foreach($cards as $card)
 <a href="{{ route($card["route"]) }}"
 class="bg-white rounded-lg shadow p-6 border-l-4 border-{{ $card["color"] }}-600 hover:shadow-lg transition">
 <div class="text-2xl font-bold text-blue-900 mb-2">{{ $card["title"] }}</div>
 <div class="text-sm text-gray-600 mb-4">{{ $card["desc"] }}</div>
 <div class="text-xs text-blue-700 font-semibold">Open </div>
 </a>
 @endforeach
 </div>

 <div class="mt-8 bg-blue-50 border-l-4 border-blue-600 p-4 rounded">
 <strong class="text-blue-900">Need something else?</strong>
 <p class="text-sm text-gray-700 mt-1">Some tools are shared with all staff:
 <a href="{{ route("shared.dashboard") }}" class="text-blue-700 underline">Staff Dashboard</a>,
 <a href="{{ route("shared.my-attendance") }}" class="text-blue-700 underline">My Attendance</a>,
 <a href="{{ route("shared.directory") }}" class="text-blue-700 underline">Directory</a>.
 </p>
 </div>
 @else
 <div class="bg-yellow-100 border-l-4 border-yellow-600 p-6 rounded">
 <strong>No department assigned.</strong>
 <p class="mt-2 text-sm">Contact the school admin to be assigned to a department (Health, Discipline, Sports, etc.).</p>
 </div>
 @endif
@endsection