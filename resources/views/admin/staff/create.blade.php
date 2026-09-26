@extends("layouts.app")
@section("title", "Add Staff")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Add Staff</h1>

    <form method="POST" action="{{ route("admin.staff.store") }}" class="bg-white rounded shadow p-6 space-y-6 max-w-4xl">
        @csrf

        {{-- ============ STEP 1: Basic Info ============ --}}
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

        {{-- ============ STEP 2: Employee Type ============ --}}
        <h3 class="font-bold text-blue-900 border-b pb-2">Employment Category</h3>

        <div>
            <label class="block font-semibold mb-2">Is this staff a teacher or non-teaching?</label>
            <div class="flex gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="staff_category" value="teaching" id="cat_teaching"
                           {{ old("staff_category") === "teaching" ? "checked" : "" }} onchange="toggleCategory()">
                    <span class="font-semibold">Teaching Staff (Teacher)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="staff_category" value="non_teaching" id="cat_non_teaching"
                           {{ old("staff_category", "non_teaching") === "non_teaching" ? "checked" : "" }} onchange="toggleCategory()">
                    <span class="font-semibold">Non-Teaching Staff</span>
                </label>
            </div>
        </div>

        {{-- Staff type — filtered per category --}}
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Staff Type</label>
                <select name="staff_type" id="staff_type" class="w-full border rounded px-3 py-2" required onchange="updateDept()">
                    <option value="">-- Select --</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Primary Department</label>
                <select name="primary_department_id" id="primary_department_id" class="w-full border rounded px-3 py-2" onchange="loadSubjects()">
                    <option value="">-- Select --</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" data-type="{{ $d->type }}"
                                {{ old("primary_department_id") == $d->id ? "selected" : "" }}>
                            {{ $d->name }} ({{ $d->type === "teaching" ? "Teaching" : "Non-Teaching" }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Role Title</label>
                <input type="text" name="role_title" value="{{ old("role_title") }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        {{-- ============ TEACHING-ONLY FIELDS ============ --}}
        <div id="teaching_fields" class="hidden space-y-4">
            <h3 class="font-bold text-blue-900 border-b pb-2">Teaching Details</h3>

            <div>
                <label class="block font-semibold mb-2">Subjects Taught</label>
                <div id="subjects_container" class="grid grid-cols-3 gap-3">
                    <p class="text-gray-500 text-sm">Select a department first to load subjects.</p>
                </div>
            </div>

            <div>
                <label class="block font-semibold mb-2">Classes Taught</label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach($classrooms as $room)
                        <label class="flex items-center gap-2 border rounded px-3 py-2 cursor-pointer hover:bg-blue-50">
                            <input type="checkbox" name="classrooms[]" value="{{ $room->id }}"
                                   {{ in_array($room->id, old("classrooms", [])) ? "checked" : "" }}>
                            <span>{{ $room->name }}{{ $room->stream ? " (".$room->stream.")" : "" }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block font-semibold mb-2">Class Teacher Of (Optional)</label>
                <select name="class_teacher_of" class="w-full border rounded px-3 py-2">
                    <option value="">-- Not a class teacher --</option>
                    @foreach($classrooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->level }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold mb-2">Additional Department Roles (Optional)</label>
                <p class="text-sm text-gray-600 mb-2">Extra responsibilities this teacher also handles.</p>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(\App\Models\Staff::departmentRoleOptions() as $key => $label)
                        @if($key !== "academic")
                            <label class="flex items-center gap-2 border rounded px-3 py-2 cursor-pointer hover:bg-blue-50">
                                <input type="checkbox" name="extra_roles[]" value="{{ $key }}"
                                       {{ in_array($key, old("extra_roles", [])) ? "checked" : "" }}>
                                <span class="text-sm">{{ $label }}</span>
                            </label>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ============ NON-TEACHING-ONLY FIELDS ============ --}}
        <div id="non_teaching_fields" class="hidden space-y-4">
            <h3 class="font-bold text-blue-900 border-b pb-2">Non-Teaching Details</h3>
            <p class="text-sm text-gray-600">This staff member works in a non-teaching capacity. No subjects or classes required.</p>
        </div>

        {{-- ============ EMPLOYMENT DETAILS ============ --}}
        <h3 class="font-bold text-blue-900 border-b pb-2">Employment</h3>

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
        const teachingTypes = [
            "Teacher", "Head of Department", "Academic Master", "Lab Technician"
        ];
        const nonTeachingTypes = [
            "Bursar", "Accounts Clerk", "Head of School", "Deputy Head",
            "Secretary", "Librarian", "ICT Officer", "Nurse",
            "Cleaner", "Security Guard", "Cook"
        ];

        function toggleCategory() {
            const isTeaching = document.getElementById("cat_teaching").checked;
            const typeSelect = document.getElementById("staff_type");
            const teachingFields = document.getElementById("teaching_fields");
            const nonTeachingFields = document.getElementById("non_teaching_fields");

            // Rebuild staff type options
            typeSelect.innerHTML = '<option value="">-- Select --</option>';
            const types = isTeaching ? teachingTypes : nonTeachingTypes;
            types.forEach(t => {
                const opt = document.createElement("option");
                opt.value = t;
                opt.textContent = t;
                typeSelect.appendChild(opt);
            });

            // Filter department dropdown
            filterDepartments(isTeaching);

            // Show/hide fields
            if (isTeaching) {
                teachingFields.classList.remove("hidden");
                nonTeachingFields.classList.add("hidden");
            } else {
                teachingFields.classList.add("hidden");
                nonTeachingFields.classList.remove("hidden");
            }
        }

        function filterDepartments(isTeaching) {
            const deptSelect = document.getElementById("primary_department_id");
            const wanted = isTeaching ? "teaching" : "non_teaching";
            Array.from(deptSelect.options).forEach(opt => {
                if (opt.value === "") return;
                opt.style.display = opt.dataset.type === wanted ? "" : "none";
            });
            deptSelect.value = "";
        }

        function updateDept() {
            // Not used anymore but kept for compatibility
        }

        function loadSubjects() {
            const deptId = document.getElementById("primary_department_id").value;
            const container = document.getElementById("subjects_container");

            if (!deptId) {
                container.innerHTML = '<p class="text-gray-500 text-sm">Select a department first.</p>';
                return;
            }

            container.innerHTML = '<p class="text-gray-500 text-sm">Loading...</p>';

            fetch("{{ route("admin.staff.subjects.byDepartment") }}?department_id=" + deptId, {
                headers: { "Accept": "application/json" }
            })
            .then(res => res.json())
            .then(data => {
                if (!data.length) {
                    container.innerHTML = '<p class="text-gray-500 text-sm">No subjects in this department.</p>';
                    return;
                }
                let html = "";
                data.forEach(s => {
                    html += `<label class="flex items-center gap-2 border rounded px-3 py-2 cursor-pointer hover:bg-blue-50">
                        <input type="checkbox" name="subjects[]" value="${s.id}">
                        <span class="text-sm">${s.name} (${s.code})</span>
                    </label>`;
                });
                container.innerHTML = html;
            })
            .catch(() => {
                container.innerHTML = '<p class="text-red-500 text-sm">Error loading subjects.</p>';
            });
        }

        // Init on load
        window.addEventListener("DOMContentLoaded", function () {
            toggleCategory();
        });
    </script>
@endsection