@extends("layouts.app")
@section("title", "SMS Templates")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">SMS Templates</h1>
 <a href="{{ route("admin.sms-templates.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded">Add Template</a>
 </div>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
 <select name="key" class="border rounded px-3 py-2">
 <option value="">All Keys</option>
 @foreach($keys as $k)
 <option value="{{ $k }}" {{ request("key") === $k ? "selected" : "" }}>{{ $k }}</option>
 @endforeach
 </select>
 <select name="language" class="border rounded px-3 py-2">
 <option value="">All Languages</option>
 <option value="en" {{ request("language") === "en" ? "selected" : "" }}>English</option>
 <option value="sw" {{ request("language") === "sw" ? "selected" : "" }}>Kiswahili</option>
 </select>
 <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
 <a href="{{ route("admin.sms-templates.index") }}" class="text-gray-600 px-4 py-2">Reset</a>
 </form>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Key</th>
 <th class="p-3 text-left">Name</th>
 <th class="p-3 text-left">Language</th>
 <th class="p-3 text-left">Body</th>
 <th class="p-3 text-left">Active</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($templates as $t)
 <tr class="border-b">
 <td class="p-3 font-mono text-xs">{{ $t->key }}</td>
 <td class="p-3">{{ $t->name }}</td>
 <td class="p-3">
 <span class="text-xs px-2 py-0.5 rounded {{ $t->language === "sw" ? "bg-green-100 text-green-800" : "bg-blue-100 text-blue-800" }}">
 {{ $t->language === "sw" ? "SW" : "EN" }}
 </span>
 </td>
 <td class="p-3 text-xs text-gray-600">{{ \Illuminate\Support\Str::limit($t->body, 80) }}</td>
 <td class="p-3">{{ $t->is_active ? "Yes" : "No" }}</td>
 <td class="p-3">
 <a href="{{ route("admin.sms-templates.edit", $t) }}" class="text-yellow-700 hover:underline">Edit</a>
 <form method="POST" action="{{ route("admin.sms-templates.destroy", $t) }}" class="inline ml-2" onsubmit="return confirm(&quot;Delete?&quot;);">
 @csrf @method("DELETE")
 <button class="text-red-700 hover:underline">Delete</button>
 </form>
 </td>
 </tr>
 @empty
 <tr><td colspan="6" class="p-6 text-center text-gray-500">No templates.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $templates->links() }}</div>
@endsection