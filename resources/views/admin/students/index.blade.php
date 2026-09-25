@extends("layouts.app")
@section("title", "Students")
@section("content")
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-900">Students</h1>
        <a href="{{ route("admin.students.create") }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">Admit Student</a>
    </div>

    {{-- FILTER BAR --}}
    <form method="GET" class="bg-white rounded shadow p-4 mb-4 flex flex-wrap gap-3 items-center">
        <select name="login_status" class="border rounded px-3 py-2">
            <option value="">All Students</option>
            <option value="enabled"  {{ request("login_status") === "enabled"  ? "selected" : "" }}>Login Enabled</option>
            <option value="disabled" {{ request("login_status") === "disabled" ? "selected" : "" }}>No Login</option>
        </select>
        <select name="level" class="border rounded px-3 py-2">
            <option value="">All Levels</option>
            @foreach($levels as $lvl)
                <option value="{{ $lvl }}" {{ request("level") === $lvl ? "selected" : "" }}>
                    {{ \App\Services\Level\SchoolLevelService::label($lvl) }}
                </option>
            @endforeach
        </select>
        <button class="bg-blue-900 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route("admin.students.index") }}" class="text-gray-600 px-4 py-2">Reset</a>

        {{-- BULK ALL BUTTONS --}}
        <div class="ml-auto flex gap-2">
            <form method="POST" action="{{ route("admin.students.enable-all") }}" class="inline"
                  onsubmit="return confirm('Enable login for ALL eligible students? This may create many accounts.');">
                @csrf
                <input type="hidden" name="level" value="{{ request("level") }}">
                <button class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 text-sm">Enable Login — All</button>
            </form>
            <form method="POST" action="{{ route("admin.students.disable-all") }}" class="inline"
                  onsubmit="return confirm('Disable login for ALL students? This will delete all student user accounts.');">
                @csrf
                <input type="hidden" name="level" value="{{ request("level") }}">
                <button class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800 text-sm">Disable Login — All</button>
            </form>
        </div>
    </form>

    {{-- BULK ACTION FORM (selection) --}}
    <form method="POST" id="bulk-form" action="">
        @csrf
        <div id="bulk-actions" class="bg-blue-50 border-l-4 border-blue-600 p-3 mb-3 rounded hidden">
            <div class="flex items-center gap-3 flex-wrap">
                <span id="selected-count" class="font-semibold text-blue-900">0 selected</span>
                <button type="submit" formaction="{{ route("admin.students.bulk-enable") }}"
                        onclick="return confirm('Enable login for selected students?');"
                        class="bg-green-700 text-white px-4 py-1.5 rounded text-sm">Enable Selected</button>
                <button type="submit" formaction="{{ route("admin.students.bulk-disable") }}"
                        onclick="return confirm('Disable login for selected students?');"
                        class="bg-red-700 text-white px-4 py-1.5 rounded text-sm">Disable Selected</button>
                <button type="button" onclick="clearSelection()" class="text-gray-600 text-sm underline">Clear</button>
            </div>
        </div>

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-blue-900 text-white">
                    <tr>
                        <th class="p-3 w-10">
                            <input type="checkbox" id="select-all" class="w-4 h-4">
                        </th>
                        <th class="p-3 text-left">Adm No</th>
                        <th class="p-3 text-left">Name</th>
                        <th class="p-3 text-left">Level</th>
                        <th class="p-3 text-left">Class</th>
                        <th class="p-3 text-left">Parent Phone</th>
                        <th class="p-3 text-left">Login</th>
                        <th class="p-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">
                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="row-checkbox w-4 h-4">
                            </td>
                            <td class="p-3">{{ $student->admission_no }}</td>
                            <td class="p-3">{{ $student->full_name }}</td>
                            <td class="p-3">{{ ucfirst($student->level) }}</td>
                            <td class="p-3">{{ $student->classroom?->name ?? "-" }}</td>
                            <td class="p-3">{{ $student->parent_phone }}</td>
                            <td class="p-3">
                                @if($student->can_login)
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Enabled</span>
                                @else
                                    <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded">Disabled</span>
                                @endif
                            </td>
                            <td class="p-3 space-x-2">
                                <a href="{{ route("admin.students.show", $student) }}" class="text-blue-700 hover:underline">View</a>
                                <a href="{{ route("admin.students.edit", $student) }}" class="text-yellow-700 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="p-6 text-center text-gray-500">No students.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    <div class="mt-4">{{ $students->links() }}</div>

    <script>
        (function () {
            const selectAll     = document.getElementById("select-all");
            const rowCheckboxes = document.querySelectorAll(".row-checkbox");
            const bulkActions   = document.getElementById("bulk-actions");
            const selectedCount = document.getElementById("selected-count");

            function updateUI() {
                const checked = document.querySelectorAll(".row-checkbox:checked").length;
                if (checked > 0) {
                    bulkActions.classList.remove("hidden");
                    selectedCount.textContent = checked + " selected";
                } else {
                    bulkActions.classList.add("hidden");
                }
                selectAll.checked = rowCheckboxes.length > 0 && checked === rowCheckboxes.length;
            }

            selectAll.addEventListener("change", function () {
                rowCheckboxes.forEach(cb => cb.checked = this.checked);
                updateUI();
            });

            rowCheckboxes.forEach(cb => cb.addEventListener("change", updateUI));

            window.clearSelection = function () {
                rowCheckboxes.forEach(cb => cb.checked = false);
                selectAll.checked = false;
                updateUI();
            };
        })();
    </script>
@endsection