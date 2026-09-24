@extends("layouts.app")
@section("title", "Add Staff")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Staff</h1>

    <form method="POST" action="{{ route("admin.staff.store") }}" class="bg-white rounded shadow p-6 space-y-6 max-w-4xl">
        @csrf

        <h3 class="font-bold text-blue-900 border-b pb-2">Personal Information</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">First Name</label>
                <input type="text" name="first_name" value="{{ old("first_name") }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Last Name</label>
                <input type="text" name="last_name" value="{{ old("last_name") }}" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Gender</label>
                <select name="gender" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Select --</option>
                    <option value="male" {{ old("gender") === "male" ? "selected" : "" }}>Male</option>
                    <option value="female" {{ old("gender") === "female" ? "selected" : "" }}>Female</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Date of Birth</label>
                <input type="date" name="dob" value="{{ old("dob") }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">NIDA</label>
                <input type="text" name="nida" value="{{ old("nida") }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old("phone") }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" value="{{ old("email") }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Address</label>
            <input type="text" name="address" value="{{ old("address") }}" class="w-full border rounded px-3 py-2">
        </div>

        <h3 class="font-bold text-blue-900 border-b pb-2">Employment</h3>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Staff Type</label>
                <select name="staff_type" id="staff_type" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Select Type --</option>
                    @foreach($types as $type => $dept)
                        <option value="{{ $type }}" data-dept="{{ $dept }}" {{ old("staff_type") === $type ? "selected" : "" }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Department</label>
                <input type="text" id="department" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
            </div>
            <div>
                <label class="block font-semibold mb-1">Role Title</label>
                <input type="text" name="role_title" value="{{ old("role_title") }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Employment Date</label>
                <input type="date" name="employment_date" value="{{ old("employment_date") }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Employment Type</label>
                <select name="employment_type" class="w-full border rounded px-3 py-2">
                    <option value="">-- Select --</option>
                    <option value="full-time" {{ old("employment_type") === "full-time" ? "selected" : "" }}>Full-time</option>
                    <option value="part-time" {{ old("employment_type") === "part-time" ? "selected" : "" }}>Part-time</option>
                    <option value="contract"  {{ old("employment_type") === "contract"  ? "selected" : "" }}>Contract</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">TIN Number</label>
                <input type="text" name="tin_number" value="{{ old("tin_number") }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div id="subjects_section" class="hidden">
            <h3 class="font-bold text-blue-900 border-b pb-2">Subjects Taught</h3>
            <p class="text-sm text-gray-600 mb-3">Select all subjects this teacher handles.</p>
            <div class="grid grid-cols-3 gap-3">
                @foreach($subjects as $subject)
                    <label class="flex items-center gap-2 border rounded px-3 py-2 cursor-pointer hover:bg-blue-50">
                        <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                               {{ in_array($subject->id, old("subjects", [])) ? "checked" : "" }}>
                        <span>{{ $subject->name }} ({{ $subject->code }})</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div id="classrooms_section">
            <h3 class="font-bold text-blue-900 border-b pb-2">Assigned Classrooms</h3>
            <p class="text-sm text-gray-600 mb-3">Select the classes this teacher teaches (they will see these classes on their dashboard).</p>
            <div class="grid grid-cols-3 gap-3">
                @foreach($classrooms as $room)
                    <label class="flex items-center gap-2 border rounded px-3 py-2 cursor-pointer hover:bg-blue-50">
                        <input type="checkbox" name="classrooms[]" value="{{ $room->id }}"
                               {{ in_array($room->id, old("classrooms", [])) ? "checked" : "" }}>
                        <span>{{ $room->name }} ({{ ucfirst($room->level) }}{{ $room->stream ? " - " . $room->stream : "" }})</span>
                    </label>
                @endforeach
            </div>

            <div class="mt-4">
                <label class="block font-semibold mb-1">Class Teacher of (Optional)</label>
                <p class="text-sm text-gray-600 mb-2">Select a class if this teacher is the class teacher (guardian) of that class.</p>
                <select name="class_teacher_of" class="w-full border rounded px-3 py-2">
                    <option value="">-- Not a class teacher --</option>
                    @foreach($classrooms as $room)
                        <option value="{{ $room->id }}" {{ old("class_teacher_of") == $room->id ? "selected" : "" }}>
                            {{ $room->name }} ({{ ucfirst($room->level) }}{{ $room->stream ? " - " . $room->stream : "" }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <h3 class="font-bold text-blue-900 border-b pb-2">Education</h3>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Qualification</label>
                <input type="text" name="qualification" value="{{ old("qualification") }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Field of Study</label>
                <input type="text" name="field_of_study" value="{{ old("field_of_study") }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Year Graduated</label>
                <input type="number" name="year_graduated" value="{{ old("year_graduated") }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>
        <div>
            <label class="block font-semibold mb-1">Institution</label>
            <input type="text" name="institution" value="{{ old("institution") }}" class="w-full border rounded px-3 py-2">
        </div>

        <h3 class="font-bold text-blue-900 border-b pb-2">Emergency Contact</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Contact Name</label>
                <input type="text" name="emergency_contact_name" value="{{ old("emergency_contact_name") }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Contact Phone</label>
                <input type="text" name="emergency_contact_phone" value="{{ old("emergency_contact_phone") }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <h3 class="font-bold text-blue-900 border-b pb-2">Bank Details</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Bank Name</label>
                <input type="text" name="bank_name" value="{{ old("bank_name") }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Bank Account</label>
                <input type="text" name="bank_account" value="{{ old("bank_account") }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded hover:bg-blue-800">Save Staff</button>
    </form>

    <script>
        (function () {
            var typeSelect = document.getElementById("staff_type");
            var deptField  = document.getElementById("department");
            var subjectsSection = document.getElementById("subjects_section");
            var teacherTypes = ["Teacher","Head of Department","Academic Master","Lab Technician"];

            function update() {
                var selected = typeSelect.options[typeSelect.selectedIndex];
                deptField.value = selected.getAttribute("data-dept") || "";
                if (teacherTypes.indexOf(typeSelect.value) !== -1) {
                    subjectsSection.classList.remove("hidden");
                } else {
                    subjectsSection.classList.add("hidden");
                }
            }

            typeSelect.addEventListener("change", update);
            update();
        })();
    </script>
@endsection