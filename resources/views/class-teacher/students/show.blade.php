@extends("layouts.app")
@section("title", "Student Profile")
@section("content")
 <h1 class="text-3xl font-bold text-blue-900 mb-6">{{ $student->full_name }}</h1>

 <div class="grid grid-cols-3 gap-4 mb-6">
 <div class="bg-white rounded shadow p-4">
 <div class="text-sm text-gray-500">Admission No</div>
 <div class="text-xl font-bold">{{ $student->admission_no }}</div>
 </div>
 <div class="bg-white rounded shadow p-4">
 <div class="text-sm text-gray-500">Level</div>
 <div class="text-xl font-bold">{{ ucfirst($student->level) }}</div>
 </div>
 <div class="bg-white rounded shadow p-4">
 <div class="text-sm text-gray-500">Parent Phone</div>
 <div class="text-xl font-bold">{{ $student->parent_phone }}</div>
 </div>
 </div>

 <div class="grid grid-cols-2 gap-4">
 <div class="bg-white rounded shadow p-4">
 <h3 class="font-bold text-blue-900 mb-3">Discipline Logs</h3>
 @forelse($discipline as $d)
 <div class="border-b py-2 text-sm">
 <strong>{{ ucfirst($d->type) }}</strong> — {{ $d->reason }}
 <div class="text-xs text-gray-500">{{ $d->log_date->format("Y-m-d") }}</div>
 </div>
 @empty
 <p class="text-gray-500 text-sm">No records.</p>
 @endforelse
 </div>

 <div class="bg-white rounded shadow p-4">
 <h3 class="font-bold text-blue-900 mb-3">Welfare Notes</h3>
 @forelse($welfare as $w)
 <div class="border-b py-2 text-sm">
 <strong>{{ ucfirst($w->type) }}</strong> — {{ $w->observation }}
 <div class="text-xs text-gray-500">{{ $w->note_date->format("Y-m-d") }}</div>
 </div>
 @empty
 <p class="text-gray-500 text-sm">No records.</p>
 @endforelse
 </div>
 </div>
@endsection