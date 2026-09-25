@extends("layouts.app")
@section("title", "Parent Dashboard")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-2">Parent Dashboard</h1>
    <p class="text-gray-600 mb-6">Welcome — {{ now()->format("l, d M Y") }}</p>

    @if($children->isEmpty())
        <div class="bg-yellow-100 border-l-4 border-yellow-600 p-4 rounded mb-6">
            No children linked to your phone number ({{ auth()->user()->phone }}). Contact the school.
        </div>
    @else
        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
                <div class="text-sm text-gray-500">My Children</div>
                <div class="text-3xl font-bold text-blue-900">{{ $children->count() }}</div>
            </div>
            <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
                <div class="text-sm text-gray-500">Fees Billed</div>
                <div class="text-xl font-bold text-green-900">TZS {{ number_format($totalFees) }}</div>
            </div>
            <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
                <div class="text-sm text-gray-500">Fees Paid</div>
                <div class="text-xl font-bold text-yellow-900">TZS {{ number_format($totalPaid) }}</div>
            </div>
            <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
                <div class="text-sm text-gray-500">Balance</div>
                <div class="text-xl font-bold text-red-900">TZS {{ number_format($totalBalance) }}</div>
            </div>
        </div>

        <!-- My Children -->
        <h2 class="text-xl font-bold text-blue-900 mb-3">My Children</h2>
        <div class="grid grid-cols-2 gap-4 mb-8">
            @foreach($children as $child)
                <div class="bg-white rounded shadow p-4">
                    <div class="flex justify-between mb-2">
                        <h3 class="font-bold text-blue-900 text-lg">{{ $child->full_name }}</h3>
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ ucfirst($child->level) }}</span>
                    </div>
                    <div class="text-sm text-gray-600">
                        <div>Class: <strong>{{ $child->classroom?->name ?? "-" }}</strong></div>
                        <div>Adm No: <strong>{{ $child->admission_no }}</strong></div>
                    </div>
                    <div class="mt-3 flex gap-2 flex-wrap">
                        <a href="{{ route("parent.child", $child) }}" class="bg-blue-900 text-white px-3 py-1 rounded text-xs">View Profile</a>
                        <a href="{{ route("parent.results") }}" class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-xs">Results</a>
                        <a href="{{ route("parent.attendance") }}" class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-xs">Attendance</a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-3 gap-4 mb-8">
            <a href="{{ route("parent.promotions") }}" class="bg-white rounded shadow p-6 hover:shadow-lg text-center">
                <div class="font-bold text-blue-900 mb-1">Promotions</div>
                <div class="text-sm text-gray-600">{{ $totalPromotions }} promotion records</div>
            </a>
            <a href="{{ route("parent.fees") }}" class="bg-white rounded shadow p-6 hover:shadow-lg text-center">
                <div class="font-bold text-blue-900 mb-1">Fees & Invoices</div>
                <div class="text-sm text-gray-600">See all invoices and payments</div>
            </a>
            <a href="{{ route("parent.timetable") }}" class="bg-white rounded shadow p-6 hover:shadow-lg text-center">
                <div class="font-bold text-blue-900 mb-1">Timetable</div>
                <div class="text-sm text-gray-600">Weekly class timetable</div>
            </a>
        </div>

        <!-- Latest Announcements -->
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-blue-900 mb-4">Latest Updates from School</h2>
            @forelse($announcements as $a)
                <div class="border-b py-3">
                    <div class="font-semibold">{{ $a->title }}
                        @if($a->is_pinned)<span class="text-xs bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded ml-2">Pinned</span>@endif
                    </div>
                    <div class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($a->body, 120) }}</div>
                    <div class="text-xs text-gray-400 mt-1">{{ $a->publish_date?->format("Y-m-d") }}</div>
                </div>
            @empty
                <p class="text-gray-500">No announcements.</p>
            @endforelse
            <a href="{{ route("parent.announcements") }}" class="text-blue-700 text-sm hover:underline mt-3 inline-block">View all announcements →</a>
        </div>
    @endif
@endsection