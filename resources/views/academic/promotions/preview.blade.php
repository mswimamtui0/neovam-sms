@extends("layouts.app")
@section("title", "Preview Promotions")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-2">Preview Promotions</h1>
 <p class="text-gray-600 mb-6">Academic Year: <strong>{{ $year }}</strong> — this is a preview, nothing has changed yet.</p>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
 <select name="level" class="border rounded px-3 py-2">
 <option value="">All Levels</option>
 @foreach($levels as $lvl)
 <option value="{{ $lvl }}" {{ $level === $lvl ? "selected" : "" }}>
 {{ \App\Services\Level\SchoolLevelService::label($lvl) }}
 </option>
 @endforeach
 </select>
 <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
 <a href="{{ route("academic.promotions.preview") }}" class="text-gray-600 px-4 py-2">Reset</a>
 </form>

 <div class="grid grid-cols-5 gap-3 mb-6">
 <div class="bg-white rounded shadow p-3 border-l-4 border-green-600">
 <div class="text-xs text-gray-500">Will Promote</div>
 <div class="text-2xl font-bold text-green-800">{{ $counts["will_promote"] ?? 0 }}</div>
 </div>
 <div class="bg-white rounded shadow p-3 border-l-4 border-blue-600">
 <div class="text-xs text-gray-500">Graduated</div>
 <div class="text-2xl font-bold text-blue-800">{{ $counts["graduated"] ?? 0 }}</div>
 </div>
 <div class="bg-white rounded shadow p-3 border-l-4 border-yellow-600">
 <div class="text-xs text-gray-500">Missing Class</div>
 <div class="text-2xl font-bold text-yellow-800">{{ $counts["next_class_missing"] ?? 0 }}</div>
 </div>
 <div class="bg-white rounded shadow p-3 border-l-4 border-red-600">
 <div class="text-xs text-gray-500">No Class</div>
 <div class="text-2xl font-bold text-red-800">{{ $counts["no_class"] ?? 0 }}</div>
 </div>
 <div class="bg-white rounded shadow p-3 border-l-4 border-gray-600">
 <div class="text-xs text-gray-500">No Mapping</div>
 <div class="text-2xl font-bold text-gray-800">{{ $counts["no_mapping"] ?? 0 }}</div>
 </div>
 </div>

 @if(($counts["will_promote"] ?? 0) + ($counts["graduated"] ?? 0) > 0)
 <form method="POST" action="{{ route("academic.promotions.execute") }}" onsubmit="return confirm(&quot;Execute promotion for selected students? This moves them and sends SMS.&quot;);">
 @csrf
 <input type="hidden" name="academic_year" value="{{ $year }}">

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 w-10">
 <input type="checkbox" id="select-all" checked class="w-4 h-4">
 </th>
 <th class="p-3 text-left">Student</th>
 <th class="p-3 text-left">From Class</th>
 <th class="p-3 text-left">To Class</th>
 <th class="p-3 text-left">Status</th>
 </tr>
 </thead>
 <tbody>
 @foreach($preview as $row)
 <tr class="border-b">
 <td class="p-3">
 @if(in_array($row["status"], ["will_promote","graduated"]))
 <input type="checkbox" name="student_ids[]" value="{{ $row["student"]->id }}"
 class="row-checkbox w-4 h-4" checked>
 @endif
 </td>
 <td class="p-3">{{ $row["student"]->full_name }}</td>
 <td class="p-3">{{ $row["from_class"]?->name ?? "-" }}</td>
 <td class="p-3 font-semibold">
 @if($row["status"] === "graduated")
 <span class="text-blue-700">Graduated</span>
 @else
 {{ $row["to_class"]?->name ?? ($row["next_name"] ?? "-") }}
 @endif
 </td>
 <td class="p-3">
 @switch($row["status"])
 @case("will_promote")
 <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Will Promote</span>
 @break
 @case("graduated")
 <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Graduated</span>
 @break
 @case("next_class_missing")
 <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Next Class Missing</span>
 @break
 @case("no_class")
 <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">No Class</span>
 @break
 @case("no_mapping")
 <span class="bg-gray-200 text-gray-800 text-xs px-2 py-1 rounded">No Mapping</span>
 @break
 @endswitch
 </td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>

 <div class="bg-white rounded shadow p-4 mt-4 flex items-center gap-4">
 <label class="flex items-center gap-2">
 <input type="checkbox" name="send_sms" value="1" checked>
 <span>Send SMS to parents after promotion</span>
 </label>
 <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded hover:bg-green-800">Promote Selected Students
 </button>
 </div>
 </form>

 <script>
 (function () {
 var selectAll = document.getElementById("select-all");
 var boxes = document.querySelectorAll(".row-checkbox");
 selectAll.addEventListener("change", function () {
 boxes.forEach(b =>b.checked = this.checked);
 });
 })();
 </script>
 @else
 <div class="bg-yellow-100 border-l-4 border-yellow-600 p-4 rounded">No students are eligible for promotion right now.
 </div>
 @endif
@endsection