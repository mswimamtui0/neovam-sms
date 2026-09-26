@extends("layouts.app")
@section("title", "My Hub")
@section("content")
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-900">Welcome, {{ $staff->full_name }}</h1>
        <p class="text-gray-600 mt-1">
            {{ $isTeacher ? "Teacher" : "Non-Teaching Staff" }}
            @if($staff->primaryDepartment) &middot; {{ $staff->primaryDepartment->name }} @endif
        </p>
    </div>

    @if(empty($cards))
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
            <p class="text-yellow-800">You have no department assignments yet. Contact the administrator.</p>
        </div>
    @else
        <h2 class="text-xl font-bold text-blue-900 mb-4">Your Departments & Roles</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cards as $card)
                <a href="{{ $card["url"] }}" class="block bg-white rounded shadow hover:shadow-lg p-6 border-l-4 border-blue-600">
                    <h3 class="text-lg font-bold text-gray-900">{{ $card["title"] }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $card["subtitle"] }}</p>

                    @if(!empty($card["stats"]))
                        <dl class="mt-4 pt-4 border-t border-gray-100 space-y-1">
                            @foreach($card["stats"] as $label => $value)
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-600">{{ $label }}</dt>
                                    <dd class="font-semibold text-gray-900">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @endif
                </a>
            @endforeach
        </div>
    @endif

    <div class="mt-10 bg-white rounded shadow p-6">
        <h2 class="text-xl font-bold text-blue-900 mb-4">Quick Access</h2>
        <div class="flex flex-wrap gap-3">
            @if($isTeacher)
                <a href="{{ route("staff.workspace") }}" class="bg-blue-900 text-white px-4 py-2 rounded">My Workspace</a>
            @endif
        </div>
    </div>
</div>
@endsection