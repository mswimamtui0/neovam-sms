@extends("layouts.app")
@section("title", "Children Incidents")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Incidents</h1>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Child</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Description</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incidents as $i)
                    <tr class="border-b">
                        <td class="p-3">{{ $i->created_at->format("Y-m-d H:i") }}</td>
                        <td class="p-3">{{ $i->student?->full_name ?? "-" }}</td>
                        <td class="p-3">{{ $i->type }}</td>
                        <td class="p-3">{{ $i->description }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-6 text-center text-gray-500">No incidents.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $incidents->links() }}</div>
@endsection