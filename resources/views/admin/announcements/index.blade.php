@extends("layouts.app")
@section("title", "Announcements")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Announcements</h1>
        <a href="{{ route("admin.announcements.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">New Announcement</a>
    </div>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="p-3 text-left">Title</th>
                    <th class="p-3 text-left">Audience</th>
                    <th class="p-3 text-left">Publish Date</th>
                    <th class="p-3 text-left">Expiry</th>
                    <th class="p-3 text-left">Pinned</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $a)
                    <tr class="border-b">
                        <td class="p-3 font-semibold">{{ $a->title }}</td>
                        <td class="p-3">{{ ucfirst($a->audience) }}</td>
                        <td class="p-3">{{ $a->publish_date?->format("Y-m-d") }}</td>
                        <td class="p-3">{{ $a->expiry_date?->format("Y-m-d") ?? "-" }}</td>
                        <td class="p-3">{{ $a->is_pinned ? "Yes" : "No" }}</td>
                        <td class="p-3">
                            <form method="POST" action="{{ route("admin.announcements.destroy", $a) }}" class="inline" onsubmit="return confirm(&quot;Delete?&quot;);">
                                @csrf @method("DELETE")
                                <button class="text-red-700 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-6 text-center text-gray-500">No announcements.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $announcements->links() }}</div>
@endsection