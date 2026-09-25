@extends("layouts.app")
@section("title", "New Staff Transfer")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">New Staff Transfer</h1>

 <form method="POST" action="{{ route("admin.staff-transfers.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-3xl">
 @csrf

 <div>
 <label class="block font-semibold mb-1">Staff Member</label>
 <select name="staff_id" class="w-full border rounded px-3 py-2" required>
 <option value="">-- Select Staff --</option>
 @foreach($staffList as $s)
 <option value="{{ $s->id }}" {{ $preselect == $s->id ? "selected" : "" }}>
 {{ $s->full_name }} — {{ $s->staff_type }} ({{ $s->department }})
 </option>
 @endforeach
 </select>
 </div>

 <div>
 <label class="block font-semibold mb-1">Transfer Type</label>
 <select name="transfer_type" class="w-full border rounded px-3 py-2" required>
 <option value="internal_move">Internal Move</option>
 <option value="promotion">Promotion</option>
 <option value="demotion">Demotion</option>
 <option value="transfer_in">Transfer In</option>
 <option value="transfer_out">Transfer Out</option>
 </select>
 </div>

 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">From Position (current)</label>
 <input type="text" name="from_position" class="w-full border rounded px-3 py-2" placeholder="auto-filled">
 </div>
 <div>
 <label class="block font-semibold mb-1">To Position</label>
 <input type="text" name="to_position" class="w-full border rounded px-3 py-2">
 </div>
 </div>

 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">From Department</label>
 <input type="text" name="from_department" class="w-full border rounded px-3 py-2" placeholder="auto-filled">
 </div>
 <div>
 <label class="block font-semibold mb-1">To Department</label>
 <input type="text" name="to_department" class="w-full border rounded px-3 py-2">
 </div>
 </div>

 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">From School</label>
 <input type="text" name="from_school" class="w-full border rounded px-3 py-2">
 </div>
 <div>
 <label class="block font-semibold mb-1">To School</label>
 <input type="text" name="to_school" class="w-full border rounded px-3 py-2">
 </div>
 </div>

 <div>
 <label class="block font-semibold mb-1">Effective Date</label>
 <input type="date" name="effective_date" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required>
 </div>

 <div>
 <label class="block font-semibold mb-1">Reason</label>
 <textarea name="reason" rows="3" class="w-full border rounded px-3 py-2"></textarea>
 </div>

 <div>
 <label class="block font-semibold mb-1">Notes</label>
 <textarea name="notes" rows="2" class="w-full border rounded px-3 py-2"></textarea>
 </div>

 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Create Transfer</button>
 </form>
@endsection