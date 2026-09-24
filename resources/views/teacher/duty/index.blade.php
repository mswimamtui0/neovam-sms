@extends("layouts.app")
@section("title", "Duty Roster")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">My Duty Roster</h1>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Shift</th>
                    <th class="p-3 text-left">Location</th>
                    <th class="p-3 text-left">Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($duties as $d)
                    <tr class="border-b">
                        <td class="p-3">{{ $d->duty_date->format("Y-m-d") }}</td>
                        <td class="p-3">{{ ucfirst($d->duty_type) }}</td>
                        <td class="p-3">{{ ucfirst($d->shift) }}</td>
                        <td class="p-3">{{ $d->location ?? "-" }}</td>
                        <td class="p-3">{{ $d->notes ?? "-" }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-gray-500">No duty assigned.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $duties->links() }}</div>
@endsection