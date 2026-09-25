@extends("layouts.app")
@section("title", "Edit Transfer")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Edit Transfer #{{ $transfer->id }}</h1>

 <form method="POST" action="{{ route("admin.staff-transfers.update", $transfer) }}" class="bg-white rounded shadow p-6 space-y-4 max-w-3xl">
 @csrf @method("PUT")

 <div>
 <label class="block font-semibold mb-1">Staff</label>
 <input type="text" value="{{ $transfer->staff?->full_name }}" class="w-full border rounded px-3 py-2 bg-gray-100" disabled>
 </div>

 <div>
 <label class="block font-semibold mb-1">Transfer Type</label>
 <select name="transfer_type" class="w-full border rounded px-3 py-2" required>
 @foreach(["transfer_in","transfer_out","internal_move","promotion","demotion"] as $t)
 <option value="{{ $t }}" {{ $transfer->transfer_type === $t ? "selected" : "" }}>{{ ucfirst(str_replace("_"," ",$t)) }}</option>
 @endforeach
 </select>
 </div>

 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">From Position</label>
 <input type="text" name="from_position" value="{{ $transfer->from_position }}" class="w-full border rounded px-3 py-2">
 </div>
 <div>
 <label class="block font-semibold mb-1">To Position</label>
 <input type="text" name="to_position" value="{{ $transfer->to_position }}" class="w-full border rounded px-3 py-2">
 </div>
 </div>

 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">From Department</label>
 <input type="text" name="from_department" value="{{ $transfer->from_department }}" class="w-full border rounded px-3 py-2">
 </div>
 <div>
 <label class="block font-semibold mb-1">To Department</label>
 <input type="text" name="to_department" value="{{ $transfer->to_department }}" class="w-full border rounded px-3 py-2">
 </div>
 </div>

 <div>
 <label class="block font-semibold mb-1">Effective Date</label>
 <input type="date" name="effective_date" value="{{ $transfer->effective_date?->format("Y-m-d") }}" class="w-full border rounded px-3 py-2" required>
 </div>

 <div>
 <label class="block font-semibold mb-1">Reason</label>
 <textarea name="reason" rows="3" class="w-full border rounded px-3 py-2">{{ $transfer->reason }}</textarea>
 </div>

 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Update</button>
 </form>
@endsection