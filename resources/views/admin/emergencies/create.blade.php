@extends("layouts.app")
@section("title", "Report Incident")
@section("content")
    <h1 class="text-3xl font-bold text-red-900 mb-6">Report Incident</h1>

    <div class="bg-yellow-100 border-l-4 border-yellow-600 p-4 mb-4 rounded text-sm">
        Saving this will send an instant SMS to the student parent.
    </div>

    <form method="POST" action="{{ route("admin.emergencies.store") }}" class="bg-white rounded shadow p-6 space-y-6 max-w-4xl">
        @csrf
        <input type="hidden" name="student_id" id="student_id" value="{{ old("student_id") }}">

        <!-- SELECTION METHOD -->
        <div>
            <h3 class="font-bold text-blue-900 border-b pb-2 mb-3">Find Student</h3>

            <div class="flex gap-4 mb-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="mode" value="filter" checked onchange="toggleMode()">
                    <span>Filter by class</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="mode" value="search" onchange="toggleMode()">
                    <span>Search by name</span>
                </label>
            </div>

            <!-- MODE 1: Filter -->
            <div id="filter-mode" class="grid grid-cols-4 gap-3">
                <div>
                    <label class="block font-semibold mb-1 text-sm">Level</label>
                    <select id="level" class="w-full border rounded px-3 py-2">
                        <option value="">-- Select --</option>
                        @foreach($levels as $level)
                            <option value="{{ $level }}">{{ \App\Services\Level\SchoolLevelService::label($level) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-sm">Class</label>
                    <select id="class" class="w-full border rounded px-3 py-2">
                        <option value="">-- Select Level First --</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-sm">Stream</label>
                    <select id="stream" class="w-full border rounded px-3 py-2">
                        <option value="">--</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-sm">Student</label>
                    <select id="student-dropdown" class="w-full border rounded px-3 py-2">
                        <option value="">-- Select Class First --</option>
                    </select>
                </div>
            </div>

            <!-- MODE 2: Search -->
            <div id="search-mode" class="hidden">
                <label class="block font-semibold mb-1 text-sm">Search by name or admission no</label>
                <input type="text" id="search-input" placeholder="Type at least 2 characters..." class="w-full border rounded px-3 py-2 mb-2">
                <div id="search-results" class="border rounded max-h-60 overflow-y-auto bg-gray-50 hidden"></div>
            </div>
        </div>

        <!-- SELECTED STUDENT CARD -->
        <div id="selected-card" class="hidden border-2 border-blue-900 rounded p-4 bg-blue-50">
            <h3 class="font-bold text-blue-900 mb-2">Selected Student</h3>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <p><strong>Name:</strong> <span id="sel-name"></span></p>
                <p><strong>Adm No:</strong> <span id="sel-adm"></span></p>
                <p><strong>Class:</strong> <span id="sel-class"></span></p>
                <p><strong>Level:</strong> <span id="sel-level"></span></p>
                <p><strong>Parent:</strong> <span id="sel-parent"></span></p>
                <p><strong>Parent Phone:</strong> <span id="sel-phone"></span></p>
            </div>
        </div>

        <!-- INCIDENT DETAILS -->
        <div>
            <h3 class="font-bold text-blue-900 border-b pb-2 mb-3">Incident Details</h3>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Type</label>
                <select name="type" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Select --</option>
                    <option value="sickness"   {{ old("type") === "sickness"   ? "selected" : "" }}>Sickness</option>
                    <option value="accident"   {{ old("type") === "accident"   ? "selected" : "" }}>Accident</option>
                    <option value="discipline" {{ old("type") === "discipline" ? "selected" : "" }}>Discipline</option>
                    <option value="other"      {{ old("type") === "other"      ? "selected" : "" }}>Other</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold mb-1">Description</label>
                <textarea name="description" rows="4" class="w-full border rounded px-3 py-2" required>{{ old("description") }}</textarea>
            </div>
        </div>

        <button type="submit" class="bg-red-700 text-white px-6 py-2 rounded hover:bg-red-800">Report and Send SMS</button>
    </form>

    <script>
    (function () {
        var endpoints = {
            classes:  "{{ route("admin.api.classes.byLevel") }}",
            streams:  "{{ route("admin.api.streams.byClass") }}",
            students: "{{ route("admin.api.students.byClass") }}",
            search:   "{{ route("admin.api.students.search") }}"
        };

        var levelSelect     = document.getElementById("level");
        var classSelect     = document.getElementById("class");
        var streamSelect    = document.getElementById("stream");
        var studentSelect   = document.getElementById("student-dropdown");
        var hiddenStudentId = document.getElementById("student_id");
        var searchInput     = document.getElementById("search-input");
        var searchResults   = document.getElementById("search-results");
        var selectedCard    = document.getElementById("selected-card");

        // Mode toggle
        window.toggleMode = function () {
            var mode = document.querySelector("input[name=mode]:checked").value;
            document.getElementById("filter-mode").classList.toggle("hidden", mode !== "filter");
            document.getElementById("search-mode").classList.toggle("hidden", mode !== "search");
        };

        // Fetch helper
        function get(url) {
            return fetch(url, { headers: { "Accept": "application/json" } }).then(r => r.json());
        }

        // Level change -> load classes
        levelSelect.addEventListener("change", function () {
            classSelect.innerHTML = "<option value=\"\">-- Loading --</option>";
            streamSelect.innerHTML = "<option value=\"\">--</option>";
            studentSelect.innerHTML = "<option value=\"\">-- Select Class First --</option>";

            if (!this.value) {
                classSelect.innerHTML = "<option value=\"\">-- Select Level First --</option>";
                return;
            }

            get(endpoints.classes + "?level=" + encodeURIComponent(this.value)).then(function (data) {
                var html = "<option value=\"\">-- Select Class --</option>";
                data.forEach(function (c) {
                    html += "<option value=\"" + c.id + "\">" + c.name + "</option>";
                });
                classSelect.innerHTML = html;
            });
        });

        // Class change -> load streams + students
        classSelect.addEventListener("change", function () {
            streamSelect.innerHTML = "<option value=\"\">-- Loading --</option>";
            studentSelect.innerHTML = "<option value=\"\">-- Loading --</option>";

            if (!this.value) {
                streamSelect.innerHTML = "<option value=\"\">--</option>";
                studentSelect.innerHTML = "<option value=\"\">-- Select Class First --</option>";
                return;
            }

            // Load streams
            get(endpoints.streams + "?class_id=" + this.value).then(function (data) {
                var html = "<option value=\"\">All Streams</option>";
                data.forEach(function (s) {
                    html += "<option value=\"" + s + "\">" + s + "</option>";
                });
                streamSelect.innerHTML = html;
                loadStudents(classSelect.value, null);
            });
        });

        // Stream change -> reload students
        streamSelect.addEventListener("change", function () {
            loadStudents(classSelect.value, this.value);
        });

        function loadStudents(classId, stream) {
            if (!classId) return;
            studentSelect.innerHTML = "<option value=\"\">-- Loading --</option>";

            var url = endpoints.students + "?class_id=" + classId;
            if (stream) url += "&stream=" + encodeURIComponent(stream);

            get(url).then(function (data) {
                if (!data.length) {
                    studentSelect.innerHTML = "<option value=\"\">-- No students --</option>";
                    return;
                }

                var html = "<option value=\"\">-- Select Student --</option>";
                data.forEach(function (s) {
                    html += "<option value=\"" + s.id + "\" data-student='" + JSON.stringify(s) + "'>" + s.first_name + " " + s.last_name + " (" + s.admission_no + ")</option>";
                });
                studentSelect.innerHTML = html;
            });
        }

        // Student select -> show card
        studentSelect.addEventListener("change", function () {
            var opt = this.options[this.selectedIndex];
            var raw = opt.getAttribute("data-student");
            if (!raw) {
                selectedCard.classList.add("hidden");
                hiddenStudentId.value = "";
                return;
            }
            var s = JSON.parse(raw);
            showCard(s);
            hiddenStudentId.value = s.id;
        });

        function showCard(s) {
            document.getElementById("sel-name").textContent   = s.first_name + " " + s.last_name;
            document.getElementById("sel-adm").textContent    = s.admission_no;
            document.getElementById("sel-class").textContent  = (s.classroom_name || "-") + (s.classroom_stream ? " (" + s.classroom_stream + ")" : "");
            document.getElementById("sel-level").textContent  = s.level;
            document.getElementById("sel-parent").textContent = s.parent_name;
            document.getElementById("sel-phone").textContent  = s.parent_phone;
            selectedCard.classList.remove("hidden");
        }

        // Search
        var searchTimeout = null;
        searchInput.addEventListener("input", function () {
            clearTimeout(searchTimeout);
            var q = this.value.trim();

            if (q.length < 2) {
                searchResults.classList.add("hidden");
                return;
            }

            searchTimeout = setTimeout(function () {
                get(endpoints.search + "?q=" + encodeURIComponent(q)).then(function (data) {
                    if (!data.length) {
                        searchResults.innerHTML = "<div class=\"p-3 text-gray-500\">No students found.</div>";
                        searchResults.classList.remove("hidden");
                        return;
                    }

                    var html = "";
                    data.forEach(function (s) {
                        var cls = (s.classroom_name || "-") + (s.classroom_stream ? " (" + s.classroom_stream + ")" : "");
                        html += "<div class=\"p-3 border-b hover:bg-blue-100 cursor-pointer\" data-student='" + JSON.stringify(s) + "'>" +
                            "<strong>" + s.first_name + " " + s.last_name + "</strong> (" + s.admission_no + ") - " + cls +
                            "</div>";
                    });
                    searchResults.innerHTML = html;
                    searchResults.classList.remove("hidden");

                    // Click handler
                    searchResults.querySelectorAll("[data-student]").forEach(function (el) {
                        el.addEventListener("click", function () {
                            var s = JSON.parse(this.getAttribute("data-student"));
                            showCard(s);
                            hiddenStudentId.value = s.id;
                            searchResults.classList.add("hidden");
                        });
                    });
                });
            }, 300);
        });
    })();
    </script>
@endsection