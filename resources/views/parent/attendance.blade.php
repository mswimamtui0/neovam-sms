@extends("layouts.app")
@section("title", "Children Attendance")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Children Attendance</h1>
    @foreach($children as $child)
        <div class="bg-white rounded shadow p-4 mb-4">
            <h2 class="font-bold text-blue-900 mb-2">{{ $child->full_name }}</h2>
            @forelse($child->attendances->take(30) as $a)
                <div class="text-sm border-b py-1">
                    {{ $a->date->format("Y-m-d") }} -
                    <span class="{{ $a->status === "absent" ? "text-red-600" : "text-green-600" }}">
                        {{ ucfirst($a->status) }}
                    </span>
                </div>
            @empty
                <p class="text-gray-500 text-sm">No attendance records.</p>
            @endforelse
        </div>
    @endforeach
@endsection