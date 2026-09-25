@extends("layouts.app")
@section("title", "Edit Timetable Entry")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Edit Timetable Entry</h1>
 <form method="POST" action="{{ route("academic.timetable.update", $timetable) }}" class="bg-white rounded shadow p-6 space-y-4 max-w-3xl">
 @csrf @method("PUT")
 <div class="grid grid-cols-3 gap-4">
 <div>
 <label class="block font-semibold mb-1">Teacher</label>
 <select name="staff_id" class="w-full border rounded px-3 py-2" required>
 @foreach($teachers as $t)
 <option value="{{ $t->id }}" {{ $timetable->staff_id == $t->id ? "selected" : "" }}>{{ $t->full_name }}</option>
 @endforeach
 </select>
 </div>
 <div>
 <label class="block font-semibold mb-1">Class</label>
 <select name="classroom_id" class="w-full border rounded px-3 py-2" required>
 @foreach($classrooms as $c)
 <option value="{{ $c->id }}" {{ $timetable->classroom_id == $c->id ? "selected" : "" }}>{{ $c->name }}</option>
 @endforeach
 </select>
 </div>
 <div>
 <label class="block font-semibold mb-1">Subject</label>
 <select name="subject_id" class="w-full border rounded px-3 py-2">
 <option value="">-- Optional --</option>
 @foreach($subjects as $s)
 <option value="{{ $s->id }}" {{ $timetable->subject_id == $s->id ? "selected" : "" }}>{{ $s->name }}</option>
 @endforeach
 </select>
 </div>
 </div>

 <div class="grid grid-cols-5 gap-4">
 <div>
 <label class="block font-semibold mb-1">Day</label>
 <select name="day_of_week" class="w-full border rounded px-3 py-2" required>
 @foreach($days as $d)
 <option value="{{ $d }}" {{ $timetable->day_of_week === $d ? "selected" : "" }}>{{ $d }}</option>
 @endforeach
 </select>
 </div>
 <div>
 <label class="block font-semibold mb-1">Start</label>
 <input type="time" name="start_time" value="{{ $timetable->start_time }}" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">End</label>
 <input type="time" name="end_time" value="{{ $timetable->end_time }}" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Period</label>
 <input type="text" name="period_label" value="{{ $timetable->period_label }}" class="w-full border rounded px-3 py-2">
 </div>
 <div>
 <label class="block font-semibold mb-1">Room</label>
 <input type="text" name="room" value="{{ $timetable->room }}" class="w-full border rounded px-3 py-2">
 </div>
 </div>

 <div>
 <label class="block font-semibold mb-1">Notes</label>
 <textarea name="notes" rows="2" class="w-full border rounded px-3 py-2">{{ $timetable->notes }}</textarea>
 </div>

 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Update</button>
 </form>
@endsection