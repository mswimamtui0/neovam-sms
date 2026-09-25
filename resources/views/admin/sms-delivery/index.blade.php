@extends("layouts.app")
@section("title", "SMS Delivery Reports")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">SMS Delivery Reports</h1>
        <a href="{{ route("admin.communication.index") }}" class="bg-gray-700 text-white px-4 py-2 rounded">Communication Hub</a>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
            <div class="text-sm text-gray-500">Total SMS</div>
            <div class="text-2xl font-bold text-blue-900">{{ number_format($stats["total"]) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
            <div class="text-sm text-gray-500">Delivered</div>
            <div class="text-2xl font-bold text-green-900">{{ number_format($stats["delivered"]) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
            <div class="text-sm text-gray-500">Undelivered</div>
            <div class="text-2xl font-bold text-yellow-900">{{ number_format($stats["undelivered"]) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
            <div class="text-sm text-gray-500">Failed</div>
            <div class="text-2xl font-bold text-red-900">{{ number_format($stats["failed"]) }}</div>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex flex-wrap gap-3 items-center">
        <select name="status" class="border rounded px-3 py-2">
            <option value="">All Status</option>
            @foreach(["sent","failed","pending"] as $s)
                <option value="{{ $s }}" {{ request("status") === $s ? "selected" : "" }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>

        <select name="delivery_status" class="border rounded px-3 py-2">
            <option value="">All Delivery</option>
            @foreach(["delivered","undelivered","rejected","expired","sent","pending"] as $s)
                <option value="{{ $s }}" {{ request("delivery_status") === $s ? "selected" : "" }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>

        <select name="trigger" class="border rounded px-3 py-2">
            <option value="">All Triggers</option>
            @foreach($triggers as $t)
                <option value="{{ $t }}" {{ request("trigger") === $t ? "selected" : "" }}>{{ $t }}</option>
            @endforeach
        </select>

        <input type="date" name="date" value="{{ request("date") }}" class="border rounded px-3 py-2">

        <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route("admin.sms-delivery.index") }}" class="text-gray-600 px-4 py-2">Reset</a>
    </form>

    {{-- Bulk retry form --}}
    <form method="POST" id="retry-form" action="{{ route("admin.sms-delivery.retry") }}">
        @csrf
        <div id="retry-bar" class="bg-blue-50 border-l-4 border-blue-600 p-3 mb-3 rounded hidden">
            <div class="flex items-center gap-3">
                <span id="retry-count" class="font-semibold text-blue-900">0 selected</span>
                <button type="submit" onclick="return confirm(&quot;Retry selected failed SMS?&quot;);"
                        class="bg-yellow-700 text-white px-4 py-1.5 rounded text-sm">Retry Selected</button>
                <button type="button" onclick="clearRetry()" class="text-gray-600 text-sm underline">Clear</button>
            </div>
        </div>

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-blue-900 text-white">
                    <tr>
                        <th class="p-3 w-8"><input type="checkbox" id="retry-all" class="w-4 h-4"></th>
                        <th class="p-3 text-left">Time</th>
                        <th class="p-3 text-left">Recipient</th>
                        <th class="p-3 text-left">Trigger</th>
                        <th class="p-3 text-left">Message</th>
                        <th class="p-3 text-left">Units</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Delivery</th>
                        <th class="p-3 text-left">Network</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">
                                @if($log->status === "failed")
                                    <input type="checkbox" name="ids[]" value="{{ $log->id }}" class="retry-checkbox w-4 h-4">
                                @endif
                            </td>
                            <td class="p-3 whitespace-nowrap">{{ $log->created_at->format("d M H:i") }}</td>
                            <td class="p-3">{{ $log->recipient }}</td>
                            <td class="p-3 font-mono text-xs">{{ $log->trigger }}</td>
                            <td class="p-3 text-xs">{{ \Illuminate\Support\Str::limit($log->message, 50) }}</td>
                            <td class="p-3">{{ $log->units }}</td>
                            <td class="p-3">
                                <span class="text-xs px-2 py-0.5 rounded
                                    @if($log->status === "sent") bg-green-100 text-green-800
                                    @elseif($log->status === "failed") bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                            <td class="p-3">
                                <span class="text-xs px-2 py-0.5 rounded
                                    @if($log->delivery_status === "delivered") bg-green-100 text-green-800
                                    @elseif($log->delivery_status === "undelivered") bg-yellow-100 text-yellow-800
                                    @elseif($log->delivery_status === "rejected") bg-red-100 text-red-800
                                    @else bg-gray-200 text-gray-800 @endif">
                                    {{ ucfirst($log->delivery_status) }}
                                </span>
                            </td>
                            <td class="p-3 text-xs">{{ $log->network ?? "-" }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="p-6 text-center text-gray-500">No SMS logs yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    <div class="mt-4">{{ $logs->links() }}</div>

    <script>
        (function () {
            var selectAll = document.getElementById("retry-all");
            var boxes = document.querySelectorAll(".retry-checkbox");
            var bar = document.getElementById("retry-bar");
            var count = document.getElementById("retry-count");

            function update() {
                var checked = document.querySelectorAll(".retry-checkbox:checked").length;
                bar.classList.toggle("hidden", checked === 0);
                count.textContent = checked + " selected";
                if (selectAll) selectAll.checked = boxes.length > 0 && checked === boxes.length;
            }

            if (selectAll) {
                selectAll.addEventListener("change", function () {
                    boxes.forEach(b => b.checked = this.checked);
                    update();
                });
            }
            boxes.forEach(b => b.addEventListener("change", update));

            window.clearRetry = function () {
                boxes.forEach(b => b.checked = false);
                if (selectAll) selectAll.checked = false;
                update();
            };
        })();
    </script>
@endsection