@extends("layouts.app")
@section("title", "Lesson Plans")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Lesson Plans</h1>
        <a href="{{ route("teacher.lesson-plans.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add Plan</a>
    </div>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Topic</th>
                    <th class="p-3 text-left">Class</th>
                    <th class="p-3 text-left">Subject</th>
                    <th class="p-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                    <tr class="border-b">
                        <td class="p-3">{{ $plan->date->format("Y-m-d") }}</td>
                        <td class="p-3">{{ $plan->topic }}</td>
                        <td class="p-3">{{ $plan->classroom?->name ?? "-" }}</td>
                        <td class="p-3">{{ $plan->subject?->name ?? "-" }}</td>
                        <td class="p-3">{{ ucfirst($plan->status) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-gray-500">No lesson plans yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $plans->links() }}</div>
@endsection