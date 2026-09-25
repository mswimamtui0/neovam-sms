@extends("layouts.app")
@section("title", "Backups")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Backup &amp; Restore</h1>

 <div class="grid grid-cols-5 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4 border-l-4 border-blue-600">
 <div class="text-xs text-gray-500">Total Backups</div>
 <div class="text-2xl font-bold">{{ $stats["total"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-green-600">
 <div class="text-xs text-gray-500">Completed</div>
 <div class="text-2xl font-bold text-green-800">{{ $stats["completed"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-red-600">
 <div class="text-xs text-gray-500">Failed</div>
 <div class="text-2xl font-bold text-red-800">{{ $stats["failed"] }}</div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-purple-600">
 <div class="text-xs text-gray-500">Total Size</div>
 <div class="text-2xl font-bold text-purple-800">
 {{ round($stats["total_size"] / 1024 / 1024, 2) }} MB
 </div>
 </div>
 <div class="bg-white rounded shadow p-4 border-l-4 border-yellow-600">
 <div class="text-xs text-gray-500">Last Backup</div>
 <div class="text-xs font-bold">
 {{ $stats["last_backup"]?->created_at?->diffForHumans() ?? "Never" }}
 </div>
 </div>
 </div>

 {{-- Create Backup Forms --}}
 <div class="grid grid-cols-2 gap-6 mb-6">
 <div class="bg-white rounded shadow p-6">
 <h2 class="font-bold text-blue-900 mb-3">Full System Backup</h2>
 <form method="POST" action="{{ route("admin.backups.full") }}" class="space-y-3">
 @csrf
 <input type="text" name="notes" placeholder="Notes (optional)" class="w-full border rounded px-3 py-2">
 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Create Full Backup</button>
 </form>
 <p class="text-xs text-gray-500 mt-2">Backs up ALL tables + data across the entire system.</p>
 </div>

 <div class="bg-white rounded shadow p-6">
 <h2 class="font-bold text-blue-900 mb-3">School-Specific Backup</h2>
 <form method="POST" action="{{ route("admin.backups.school") }}" class="space-y-3">
 @csrf
 <select name="school_id" class="w-full border rounded px-3 py-2" required>
 <option value="">-- Select School --</option>
 @foreach($schools as $school)
 <option value="{{ $school->id }}">{{ $school->name }}</option>
 @endforeach
 </select>
 <input type="text" name="notes" placeholder="Notes (optional)" class="w-full border rounded px-3 py-2">
 <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded">Create School Backup</button>
 </form>
 <p class="text-xs text-gray-500 mt-2">Only backs up data for a single school (for multi-tenant mode).</p>
 </div>
 </div>

 {{-- Prune --}}
 <div class="bg-white rounded shadow p-4 mb-6 flex justify-between items-center">
 <div>
 <strong class="text-blue-900">Prune Old Backups</strong>
 <span class="text-xs text-gray-500">— delete auto-backups older than N days</span>
 </div>
 <form method="POST" action="{{ route("admin.backups.prune") }}" class="flex gap-2">
 @csrf
 <input type="number" name="days" value="30" class="border rounded px-3 py-1 w-24">
 <button type="submit" class="bg-red-700 text-white px-4 py-1 rounded">Prune</button>
 </form>
 </div>

 {{-- Backups List --}}
 <h2 class="text-xl font-bold text-blue-900 mb-3">Backup History</h2>
 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Created</th>
 <th class="p-3 text-left">Filename</th>
 <th class="p-3 text-left">Type</th>
 <th class="p-3 text-left">Scope</th>
 <th class="p-3 text-right">Size</th>
 <th class="p-3 text-right">Tables</th>
 <th class="p-3 text-left">Status</th>
 <th class="p-3 text-left">Actions</th>
 </tr>
 </thead>
 <tbody>
 @forelse($backups as $b)
 <tr class="border-b hover:bg-gray-50">
 <td class="p-3 whitespace-nowrap">{{ $b->created_at->format("d M Y H:i") }}</td>
 <td class="p-3 font-mono text-xs">{{ $b->filename }}</td>
 <td class="p-3">{{ ucfirst($b->type) }}</td>
 <td class="p-3">
 {{ ucfirst($b->scope) }}
 @if($b->school) <span class="text-xs text-gray-500">— {{ $b->school->name }}</span> @endif
 </td>
 <td class="p-3 text-right">{{ $b->humanSize() }}</td>
 <td class="p-3 text-right">{{ $b->meta["table_count"] ?? "-" }}</td>
 <td class="p-3">
 <span class="text-xs px-2 py-0.5 rounded
 @if($b->status === "completed") bg-green-100 text-green-800
 @elseif($b->status === "failed") bg-red-100 text-red-800
 @else bg-yellow-100 text-yellow-800 @endif">
 {{ ucfirst($b->status) }}
 </span>
 </td>
 <td class="p-3 space-x-2">
 <a href="{{ route("admin.backups.download", $b) }}" class="text-blue-700 hover:underline">Download</a>

 <button onclick="document.getElementById('restore-modal-{{ $b->id }}').classList.remove('hidden')"
 class="text-red-700 hover:underline">Restore</button>

 <form method="POST" action="{{ route("admin.backups.destroy", $b) }}" class="inline" onsubmit="return confirm(&quot;Delete this backup?&quot;);">
 @csrf @method("DELETE")
 <button class="text-red-700 hover:underline">Delete</button>
 </form>
 </td>
 </tr>
 @empty
 <tr><td colspan="8" class="p-6 text-center text-gray-500">No backups yet.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 <div class="mt-4">{{ $backups->links() }}</div>

 {{-- Restore Modal --}}
 @foreach($backups as $b)
 <div id="restore-modal-{{ $b->id }}" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
 <div class="bg-white rounded shadow-lg max-w-md w-full p-6">
 <h3 class="text-xl font-bold text-red-800 mb-3">Restore Database</h3>
 <p class="text-sm mb-3">You are about to restore:</p>
 <div class="bg-gray-100 p-3 rounded mb-3 text-xs font-mono">{{ $b->filename }}</div>
 <p class="text-sm text-red-700 mb-4">
 <strong>Warning:</strong>This will <strong>overwrite</strong>all current data.
 A pre-restore snapshot will be taken automatically.
 </p>

 <form method="POST" action="{{ route("admin.backups.restore", $b) }}">
 @csrf
 <label class="block text-sm font-semibold mb-1">Type <code>RESTORE</code>to confirm:</label>
 <input type="text" name="confirm" class="w-full border rounded px-3 py-2 mb-3" placeholder="RESTORE" required>

 <div class="flex gap-2">
 <button type="submit" class="bg-red-700 text-white px-4 py-2 rounded flex-1">Restore Now</button>
 <button type="button"
 onclick="document.getElementById('restore-modal-{{ $b->id }}').classList.add('hidden')"
 class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
 </div>
 </form>
 </div>
 </div>
 @endforeach
@endsection