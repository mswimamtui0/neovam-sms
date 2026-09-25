@extends("layouts.app")
@section("title", "Promotion History")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Promotion History</h1>
        <a href="{{ route("academic.promotions") }}" class="text-blue-700 hover:underline">← Back</a>
    </div>

    <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
        <select name="academic_year" class="border rounded px-3 py-2">
            <option value="">All Years</option>
            @foreach($years as $y)
                <option value="{{ $y }}" {{ request("academic_year") === $y ? "selected" : "" }}>{{ $y }}</option>
            @endforeach
        </select>
        <select name="status" class="border rounded px-3 py-2">
            <option value="">All Statuses</option>
            @foreach(["promoted","graduated","repeated","transferred"] as $s)
                <option value="{{ $s }}" {{ request("status") === $s ? "selected" : "" }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route("academic.promotions.history") }}" class="text-gray-600 px-4 py-2">Reset</a>
    </form>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Student</th>
                    <th class="p-3 text-left">From</th>
                    <th class="p-3 text-left">To</th>
                    <th class="p-3 text-left">Year</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">SMS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promotions as $p)
                    <tr class="border-b">
                        <td class="p-3">{{ $p->promoted_at?->format("Y-m-d H:i") ?? "-" }}</td>
                        <td class="p-3">{{ $p->student?->full_name }}</td>
                        <td class="p-3">{{ $p->fromClassroom?->name ?? "-" }}</td>
                        <td class="p-3">{{ $p->toClassroom?->name ?? "-" }}</td>
                        <td class="p-3">{{ $p->academic_year }}</td>
                        <td class="p-3">{{ ucfirst($p->status) }}</td>
                        <td class="p-3">{{ $p->sms_sent ? "Sent" : "No" }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-6 text-center text-gray-500">No promotions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $promotions->links() }}</div>
@endsection