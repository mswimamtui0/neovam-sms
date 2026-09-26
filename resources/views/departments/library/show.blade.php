@extends("layouts.app")
@section("title", "Record - " . $department->name)
@section("content")
    <div class="mb-6">
        <a href="{{ route("dept." . $department->code . ".index") }}" class="text-blue-700 hover:underline text-sm">Back to {{ $department->name }}</a>
        <h1 class="text-3xl font-bold text-blue-900 mt-2">Record #{{ $record->id }}</h1>
    </div>

    <div class="bg-white rounded shadow p-6 max-w-3xl">
        <dl class="grid grid-cols-2 gap-4">
            @foreach($record->getAttributes() as $key => $value)
                @if(in_array($key, ["id","school_id","department_id","staff_id","created_at","updated_at"])) @continue @endif
                <div>
                    <dt class="text-sm text-gray-500">{{ ucwords(str_replace("_"," ",$key)) }}</dt>
                    <dd class="font-semibold">{{ $value ?? "-" }}</dd>
                </div>
            @endforeach
        </dl>

        <div class="mt-6 flex gap-3">
            <a href="{{ route("dept." . $department->code . ".edit", $record->id) }}"
               class="bg-yellow-600 text-white px-4 py-2 rounded">Edit</a>
            <form method="POST" action="{{ route("dept." . $department->code . ".destroy", $record->id) }}"
                  onsubmit="return confirm('Delete?');" class="inline">
                @csrf @method("DELETE")
                <button class="bg-red-700 text-white px-4 py-2 rounded">Delete</button>
            </form>
        </div>
    </div>
@endsection