@extends("layouts.app")
@section("title", "Performance Dashboard")
@section("content")
 <div class="flex justify-between items-center mb-6">
 <h1 class="text-3xl font-bold text-blue-900">Staff Performance Dashboard</h1>
 <a href="{{ route("admin.performance-reviews.index") }}" class="bg-blue-900 text-white px-4 py-2 rounded">All Reviews</a>
 </div>

 <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex gap-3">
 <select name="month" class="border rounded px-3 py-2">
 @for($m = 1; $m <= 12; $m++)
 <option value="{{ $m }}" {{ $month == $m ? "selected" : "" }}>{{ date("F", mktime(0,0,0,$m,1)) }}</option>
 @endfor
 </select>
 <input type="number" name="year" value="{{ $year }}" class="border rounded px-3 py-2 w-32">
 <button class="bg-blue-900 text-white px-4 py-2 rounded">View</button>
 </form>

 <div class="bg-white rounded shadow overflow-hidden">
 <table class="w-full text-sm">
 <thead class="bg-blue-900 text-white">
 <tr>
 <th class="p-3 text-left">Staff</th>
 <th class="p-3 text-left">Total Reports</th>
 <th class="p-3 text-left">Approved</th>
 <th class="p-3 text-left">Pending</th>
 <th class="p-3 text-left">Avg Rating</th>
 <th class="p-3 text-left">Periods Taught</th>
 <th class="p-3 text-left">Student Absences</th>
 </tr>
 </thead>
 <tbody>
 @forelse($data as $row)
 <tr class="border-b">
 <td class="p-3 font-semibold">{{ $row["staff"]->full_name }}</td>
 <td class="p-3">{{ $row["total_reports"] }}</td>
 <td class="p-3 text-green-700 font-bold">{{ $row["approved"] }}</td>
 <td class="p-3 text-yellow-700 font-bold">{{ $row["pending"] }}</td>
 <td class="p-3">
 @if($row["avg_rating"])
 <span class="text-yellow-600 font-bold">{{ $row["avg_rating"] }}/5</span>
 @else - @endif
 </td>
 <td class="p-3">{{ $row["total_periods"] }}</td>
 <td class="p-3">{{ $row["total_absent"] }}</td>
 </tr>
 @empty
 <tr><td colspan="7" class="p-6 text-center text-gray-500">No data.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
@endsection