@extends("layouts.app")
@section("title", "Add Result")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Result</h1>
 <form method="POST" action="{{ route("admin.results.store") }}" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
 @csrf
 <div>
 <label class="block font-semibold mb-1">Exam</label>
 <select name="exam_id" class="w-full border rounded px-3 py-2" required>
 <option value="">-- Select Exam --</option>
 @foreach($exams as $exam)
 <option value="{{ $exam->id }}">{{ $exam->name }} ({{ $exam->term }})</option>
 @endforeach
 </select>
 </div>
 <div>
 <label class="block font-semibold mb-1">Student</label>
 <select name="student_id" class="w-full border rounded px-3 py-2" required>
 <option value="">-- Select Student --</option>
 @foreach($students as $student)
 <option value="{{ $student->id }}">{{ $student->full_name }}</option>
 @endforeach
 </select>
 </div>
 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block font-semibold mb-1">Subject</label>
 <input type="text" name="subject" class="w-full border rounded px-3 py-2" required>
 </div>
 <div>
 <label class="block font-semibold mb-1">Marks (0–100)</label>
 <input type="number" name="marks" min="0" max="100" class="w-full border rounded px-3 py-2" required>
 </div>
 </div>
 <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded">Save Result</button>
 </form>
@endsection