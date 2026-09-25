@extends("layouts.app")
@section("title", "Children Attendance")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Children Attendance</h1>
    @foreach($children as $child)
        @php $s = $summary[$child->id]; @endphp
        <div class="bg-white rounded shadow p-6 mb-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">{{ $child->full_name }}</h2>

            <div class="grid grid-cols-4 gap-3 mb-4">
                <div class="bg-gray-100 rounded p-3">
                    <div class="text-xs text-gray-500">Total Days</div>
                    <div class="font-bold">{{ $s["total"] }}</div>
                </div>
                <div class="bg-green-100 rounded p-3">
                    <div class="text-xs text-gray-500">Present</div>
                    <div class="font-bold text-green-800">{{ $s["present"] }}</div>
                </div>
                <div class="bg-red-100 rounded p-3">
                    <div class="text-xs text-gray-500">Absent</div>
                    <div class="font-bold text-red-800">{{ $s["absent"] }}</div>
                </div>
                <div class="bg-blue-100 rounded p-3">
                    <div class="text-xs text-gray-500">Attendance Rate</div>
                    <div class="font-bold text-blue-800">{{ $s["rate"] }}%</div>
                </div>
            </div>

            <details class="mt-3">
                <summary class="cursor-pointer text-blue-700 text-sm">View full attendance record</summary>
                <div class="mt-2">
                    @forelse($child->attendances->sortByDesc("date") as $a)
                        <div class="border-b py-1 text-sm flex justify-between">
                            <span>{{ $a->date->format("Y-m-d") }}</span>
                            <span class="{{ $a->status === "absent" ? "text-red-600" : "text-green-600" }}">
                                {{ ucfirst($a->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No records.</p>
                    @endforelse
                </div>
            </details>
        </div>
    @endforeach
@endsection