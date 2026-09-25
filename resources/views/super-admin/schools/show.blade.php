@extends("layouts.app")
@section("title", "School Details")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <div>
 <h1 class="text-3xl font-bold text-blue-900">{{ $school->name }}</h1>
 <p class="text-gray-600">{{ $school->code }} | {{ $school->phone }}</p>
 </div>
 <div class="flex gap-2">
 <a href="{{ route("super-admin.schools.edit", $school) }}" class="bg-blue-900 text-white px-4 py-2 rounded">Edit</a>
 <a href="{{ route("super-admin.schools.index") }}" class="text-blue-700 px-4 py-2 hover:underline">Back</a>
 </div>
 </div>

 <div class="grid grid-cols-6 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-xs text-gray-500">Students</div>
 <div class="text-2xl font-bold">{{ $stats["students"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-xs text-gray-500">Staff</div>
 <div class="text-2xl font-bold">{{ $stats["staff"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
 <div class="text-xs text-gray-500">Users</div>
 <div class="text-2xl font-bold">{{ $stats["users"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-xs text-gray-500">Classrooms</div>
 <div class="text-2xl font-bold">{{ $stats["classrooms"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
 <div class="text-xs text-gray-500">Invoiced</div>
 <div class="text-lg font-bold">{{ number_format($stats["invoiced"]) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-teal-600">
 <div class="text-xs text-gray-500">Collected</div>
 <div class="text-lg font-bold">{{ number_format($stats["collected"]) }}</div>
 </div>
 </div>

 <div class="grid grid-cols-2 gap-6 mb-6">
 <div class="bg-white rounded shadow p-6">
 <h3 class="font-bold text-blue-900 mb-3">Subscription</h3>
 <p><strong>Plan:</strong> {{ ucfirst($school->subscription_plan) }}</p>
 <p><strong>Expires:</strong> {{ $school->subscription_expires_at?->format("d M Y") ?? "No expiry" }}</p>
 <p><strong>Status:</strong>
 @if($school->isSubscriptionActive())
 <span class="text-xs px-2 py-0.5 rounded bg-green-100 text-green-800">Active</span>
 @else
 <span class="text-xs px-2 py-0.5 rounded bg-red-100 text-red-800">Expired</span>
 @endif
 </p>
 </div>

 <div class="bg-white rounded shadow p-6">
 <h3 class="font-bold text-blue-900 mb-3">SMS Balance</h3>
 <p class="text-3xl font-bold text-blue-900 mb-3">{{ number_format($school->sms_balance_units) }} units</p>
 <form method="POST" action="{{ route("super-admin.schools.topup", $school) }}" class="flex gap-2">
 @csrf
 <input type="number" name="units" placeholder="Add units" class="border rounded px-3 py-2 flex-1" required>
 <button class="bg-green-700 text-white px-4 py-2 rounded">Top Up</button>
 </form>
 </div>
 </div>

 <div class="bg-white rounded shadow p-6">
 <h3 class="font-bold text-blue-900 mb-3">School Admins</h3>
 <table class="w-full text-sm">
 <thead class="bg-gray-100">
 <tr>
 <th class="p-2 text-left">Name</th>
 <th class="p-2 text-left">Email</th>
 <th class="p-2 text-left">Roles</th>
 <th class="p-2 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($admins as $a)
 <tr class="border-b">
 <td class="p-2">{{ $a->name }}</td>
 <td class="p-2">{{ $a->email }}</td>
 <td class="p-2 text-xs">{{ $a->getRoleNames()->implode(", ") }}</td>
 <td class="p-2">
 <form method="POST" action="{{ route("super-admin.schools.reset-password", [$school, $a]) }}" class="flex gap-1">
 @csrf
 <input type="text" name="new_password" placeholder="New password" class="border rounded px-2 py-1 text-xs" required>
 <button class="bg-yellow-700 text-white px-2 py-1 rounded text-xs">Reset</button>
 </form>
 </td>
 </tr>
 @empty
 <tr><td colspan="4" class="p-4 text-center text-gray-500">No admins.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
@endsection