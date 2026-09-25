@extends("layouts.app")
@section("title", "Auto-Promotion")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Auto-Promotion</h1>
        <div class="flex gap-2">
            <a href="{{ route("academic.promotions.history") }}" class="bg-gray-700 text-white px-4 py-2 rounded">History</a>
            <a href="{{ route("academic.promotions.preview") }}" class="bg-green-700 text-white px-4 py-2 rounded">Preview Promotions</a>
            <a href="{{ route("academic.promotions.years.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add Academic Year</a>
        </div>
    </div>

    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="font-bold text-blue-900 mb-2">How it works</h2>
        <ul class="list-disc pl-5 text-sm text-gray-700 space-y-1">
            <li>Preview shows which students will move to which class</li>
            <li>Nursery → KG → Pre-Unit → Standard 1 → ... → Standard 7 → Form 1 → ... → Form 6 → Graduated</li>
            <li>Cross-level promotions: Standard 7 → Form 1 and Form 4 → Form 5</li>
            <li>SMS is sent to each parent after promotion</li>
            <li>Full history is preserved for every student</li>
        </ul>
    </div>

    <h2 class="text-xl font-bold text-blue-900 mb-3">Academic Years</h2>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Label</th>
                    <th class="p-3 text-left">Year</th>
                    <th class="p-3 text-left">Start</th>
                    <th class="p-3 text-left">End</th>
                    <th class="p-3 text-left">Current</th>
                    <th class="p-3 text-left">Closed</th>
                </tr>
            </thead>
            <tbody>
                @forelse($years as $y)
                    <tr class="border-b">
                        <td class="p-3 font-semibold">{{ $y->label }}</td>
                        <td class="p-3">{{ $y->year }}</td>
                        <td class="p-3">{{ $y->start_date?->format("Y-m-d") }}</td>
                        <td class="p-3">{{ $y->end_date?->format("Y-m-d") }}</td>
                        <td class="p-3">
                            @if($y->is_current)
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Current</span>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="p-3">{{ $y->is_closed ? "Yes" : "No" }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-6 text-center text-gray-500">No academic years yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $years->links() }}</div>
@endsection