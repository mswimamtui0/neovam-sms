@extends("layouts.app")
@section("title", "Academic Analytics")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Academic Analytics</h1>
 <a href="{{ route("admin.analytics.index") }}" class="text-blue-700 hover:underline">Back</a>
 </div>

 {{-- Grade Distribution + By Level --}}
 <div class="grid grid-cols-2 gap-6 mb-6">
 <div class="bg-white rounded shadow p-6">
 <h2 class="text-xl font-bold text-blue-900 mb-3">Grade Distribution</h2>
 <table class="w-full text-sm">
 <thead class="bg-gray-100">
 <tr>
 <th class="p-2 text-left">Grade</th>
 <th class="p-2 text-right">Count</th>
 </tr>
 </thead>
 <tbody>
 @foreach(["A","B","C","D","E","F"] as $g)
 <tr class="border-b">
 <td class="p-2 font-bold">{{ $g }}</td>
 <td class="p-2 text-right">{{ $data["grades"][$g] ?? 0 }}</td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>

 <div class="bg-white rounded shadow p-6">
 <h2 class="text-xl font-bold text-blue-900 mb-3">By Level</h2>
 <table class="w-full text-sm">
 <thead class="bg-gray-100">
 <tr>
 <th class="p-2 text-left">Level</th>
 <th class="p-2 text-right">Students</th>
 <th class="p-2 text-right">Avg Marks</th>
 </tr>
 </thead>
 <tbody>
 @foreach($data["by_level"] as $level => $row)
 <tr class="border-b">
 <td class="p-2">{{ \App\Services\Level\SchoolLevelService::label($level) }}</td>
 <td class="p-2 text-right">{{ $row["students"] }}</td>
 <td class="p-2 text-right font-bold">{{ $row["avg_marks"] }}</td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>
 </div>

 {{-- Subject Averages --}}
 <div class="bg-white rounded shadow p-6 mb-6">
 <h2 class="text-xl font-bold text-blue-900 mb-3">Subject Averages (Top 15)</h2>
 <table class="w-full text-sm">
 <thead class="bg-gray-100">
 <tr>
 <th class="p-2 text-left">Subject</th>
 <th class="p-2 text-right">Avg Marks</th>
 <th class="p-2 text-right">Results</th>
 </tr>
 </thead>
 <tbody>
 @forelse($data["subjects"] as $s)
 <tr class="border-b">
 <td class="p-2">{{ $s["subject"] }}</td>
 <td class="p-2 text-right font-bold">{{ $s["avg_marks"] }}</td>
 <td class="p-2 text-right">{{ $s["count"] }}</td>
 </tr>
 @empty
 <tr><td colspan="3" class="p-4 text-center text-gray-500">No data.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>

 {{-- Top 10 Students --}}
 <div class="bg-white rounded shadow p-6">
 <h2 class="text-xl font-bold text-blue-900 mb-3">Top 10 Students</h2>
 <table class="w-full text-sm">
 <thead class="bg-gray-100">
 <tr>
 <th class="p-2 text-left">Student</th>
 <th class="p-2 text-left">Class</th>
 <th class="p-2 text-right">Average</th>
 <th class="p-2 text-right">Subjects</th>
 </tr>
 </thead>
 <tbody>
 @forelse($data["top_students"] as $row)
 <tr class="border-b">
 <td class="p-2 font-semibold">{{ $row["student"]?->full_name ?? "-" }}</td>
 <td class="p-2 text-xs">{{ $row["student"]?->classroom?->name ?? "-" }}</td>
 <td class="p-2 text-right font-bold text-green-700">{{ $row["avg"] }}</td>
 <td class="p-2 text-right">{{ $row["subjects"] }}</td>
 </tr>
 @empty
 <tr><td colspan="4" class="p-4 text-center text-gray-500">No data.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
@endsection