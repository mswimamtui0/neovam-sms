@extends("layouts.app")
@section("title", "Edit Student")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Edit Student</h1>

    <form method="POST" action="{{ route("admin.students.update", $student) }}" class="bg-white rounded shadow p-6 space-y-4 max-w-3xl">
        @csrf
        @method("PUT")

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">First Name</label>
                <input type="text" name="first_name" value="{{ old("first_name", $student->first_name) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Last Name</label>
                <input type="text" name="last_name" value="{{ old("last_name", $student->last_name) }}" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Gender</label>
                <select name="gender" class="w-full border rounded px-3 py-2" required>
                    <option value="male" {{ old("gender", $student->gender) === "male" ? "selected" : "" }}>Male</option>
                    <option value="female" {{ old("gender", $student->gender) === "female" ? "selected" : "" }}>Female</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Date of Birth</label>
                <input type="date" name="dob" value="{{ old("dob", $student->dob?->format("Y-m-d")) }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Level</label>
                <select name="level" id="level" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Select Level --</option>
                    @foreach($levels as $level)
                        <option value="{{ $level }}" {{ old("level", $student->level) === $level ? "selected" : "" }}>
                            {{ \App\Services\Level\SchoolLevelService::label($level) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Classroom</label>
                <select name="classroom_id" id="classroom" class="w-full border rounded px-3 py-2">
                    <option value="">-- Select Level First --</option>
                </select>
            </div>
        </div>

        <hr>
        <h3 class="font-bold text-blue-900">Parent / Guardian</h3>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Parent Name</label>
                <input type="text" name="parent_name" value="{{ old("parent_name", $student->parent_name) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Parent Phone (SMS)</label>
                <input type="text" name="parent_phone" value="{{ old("parent_phone", $student->parent_phone) }}" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Parent Email</label>
            <input type="email" name="parent_email" value="{{ old("parent_email", $student->parent_email) }}" class="w-full border rounded px-3 py-2">
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded hover:bg-blue-800">Update Student</button>
    </form>

    <script>
        (function () {
            var levelSelect = document.getElementById("level");
            var classroomSelect = document.getElementById("classroom");
            var endpoint = "{{ route("admin.api.classrooms.byLevel") }}";
            var currentClassroom = "{{ old("classroom_id", $student->classroom_id) }}";

            function loadClassrooms(level, keepSelected) {
                classroomSelect.innerHTML = "<option value=\"\">-- Loading --</option>";

                if (!level) {
                    classroomSelect.innerHTML = "<option value=\"\">-- Select Level First --</option>";
                    return;
                }

                fetch(endpoint + "?level=" + encodeURIComponent(level), {
                    headers: { "Accept": "application/json" }
                })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (!data.length) {
                        classroomSelect.innerHTML = "<option value=\"\">-- No classrooms available --</option>";
                        return;
                    }

                    var html = "<option value=\"\">-- Select Classroom --</option>";
                    data.forEach(function (room) {
                        var label = room.stream ? room.name + " (" + room.stream + ")" : room.name;
                        var selected = (keepSelected && String(room.id) === String(keepSelected)) ? "selected" : "";
                        html += "<option value=\"" + room.id + "\" " + selected + ">" + label + "</option>";
                    });
                    classroomSelect.innerHTML = html;
                })
                .catch(function () {
                    classroomSelect.innerHTML = "<option value=\"\">-- Error loading --</option>";
                });
            }

            levelSelect.addEventListener("change", function () {
                loadClassrooms(this.value, null);
            });

            if (levelSelect.value) {
                loadClassrooms(levelSelect.value, currentClassroom);
            }
        })();
    </script>
@endsection