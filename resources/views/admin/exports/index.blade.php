@extends("layouts.app")
@section("title", "Reports & Exports")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Reports &amp; Exports</h1>

    <div class="grid grid-cols-2 gap-6">

        {{-- Students --}}
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">Students</h2>
            <div class="flex gap-2 mb-3">
                <a href="{{ route("admin.exports.students.excel") }}" class="bg-green-700 text-white px-4 py-2 rounded text-sm">Excel</a>
                <a href="{{ route("admin.exports.students.pdf") }}" class="bg-red-700 text-white px-4 py-2 rounded text-sm">PDF</a>
            </div>
            <p class="text-xs text-gray-500">Includes all active students + parent details. Filter by level via query string.</p>
        </div>

        {{-- Staff --}}
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">Staff</h2>
            <div class="flex gap-2 mb-3">
                <a href="{{ route("admin.exports.staff.excel") }}" class="bg-green-700 text-white px-4 py-2 rounded text-sm">Excel</a>
                <a href="{{ route("admin.exports.staff.pdf") }}" class="bg-red-700 text-white px-4 py-2 rounded text-sm">PDF</a>
            </div>
            <p class="text-xs text-gray-500">All active staff with department, type, contact.</p>
        </div>

        {{-- Payments --}}
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">Payments</h2>
            <form method="GET" class="grid grid-cols-3 gap-2 mb-3">
                <input type="date" name="from" value="{{ now()->startOfMonth()->toDateString() }}" class="border rounded px-2 py-1 text-xs">
                <input type="date" name="to"   value="{{ now()->endOfMonth()->toDateString() }}"   class="border rounded px-2 py-1 text-xs">
                <div class="flex gap-1">
                    <button type="submit" formaction="{{ route("admin.exports.payments.excel") }}" class="bg-green-700 text-white px-2 py-1 rounded text-xs">Excel</button>
                    <button type="submit" formaction="{{ route("admin.exports.payments.pdf") }}"   class="bg-red-700 text-white px-2 py-1 rounded text-xs">PDF</button>
                </div>
            </form>
            <p class="text-xs text-gray-500">Payment receipts + totals for a date range.</p>
        </div>

        {{-- Attendance --}}
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">Attendance</h2>
            <form method="GET" class="grid grid-cols-3 gap-2 mb-3">
                <input type="date" name="from" value="{{ now()->startOfMonth()->toDateString() }}" class="border rounded px-2 py-1 text-xs">
                <input type="date" name="to"   value="{{ now()->endOfMonth()->toDateString() }}"   class="border rounded px-2 py-1 text-xs">
                <div class="flex gap-1">
                    <button type="submit" formaction="{{ route("admin.exports.attendance.excel") }}" class="bg-green-700 text-white px-2 py-1 rounded text-xs">Excel</button>
                    <button type="submit" formaction="{{ route("admin.exports.attendance.pdf") }}"   class="bg-red-700 text-white px-2 py-1 rounded text-xs">PDF</button>
                </div>
            </form>
            <p class="text-xs text-gray-500">All attendance records in a date range.</p>
        </div>

        {{-- SMS --}}
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">SMS Log</h2>
            <form method="GET" class="grid grid-cols-3 gap-2 mb-3">
                <input type="date" name="from" value="{{ now()->startOfMonth()->toDateString() }}" class="border rounded px-2 py-1 text-xs">
                <input type="date" name="to"   value="{{ now()->endOfMonth()->toDateString() }}"   class="border rounded px-2 py-1 text-xs">
                <div class="flex gap-1">
                    <button type="submit" formaction="{{ route("admin.exports.sms.excel") }}" class="bg-green-700 text-white px-2 py-1 rounded text-xs">Excel</button>
                    <button type="submit" formaction="{{ route("admin.exports.sms.pdf") }}"   class="bg-red-700 text-white px-2 py-1 rounded text-xs">PDF</button>
                </div>
            </form>
            <p class="text-xs text-gray-500">SMS logs with cost, status, delivery.</p>
        </div>

        {{-- Results --}}
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-3">Results</h2>
            <form method="GET" class="flex gap-2 mb-3">
                <select name="exam_id" class="border rounded px-2 py-1 text-xs flex-1">
                    <option value="">All Exams</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->name }} ({{ $exam->term }})</option>
                    @endforeach
                </select>
                <button type="submit" formaction="{{ route("admin.exports.results.excel") }}" class="bg-green-700 text-white px-3 py-1 rounded text-xs">Excel</button>
            </form>
            <p class="text-xs text-gray-500">All results or filtered by exam.</p>
        </div>

    </div>
@endsection