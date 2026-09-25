<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield("title", "NEOVAM SMS")</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1e4fa3">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="NEOVAM SMS">
    <link rel="apple-touch-icon" href="/logo.png">
    <style>
        body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; }

        .sb-top {
            display: block;
            padding: 0.5rem 1rem 0.5rem 2rem;
            border-radius: 0.25rem;
            color: #fff;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: background-color 0.15s;
        }
        .sb-top:hover { background-color: #1e40af; }
        .sb-top.active { background-color: #1e40af; font-weight: 600; }

        .sb-link {
            display: block;
            padding: 0.4rem 0.75rem 0.4rem 2.5rem;
            border-radius: 0.25rem;
            color: #dbeafe;
            text-decoration: none;
            font-size: 0.8rem;
            transition: background-color 0.15s;
        }
        .sb-link:hover { background-color: #1e40af; color: #fff; }

        .sb-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.75rem 1rem 0.5rem;
            font-size: 0.65rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #93c5fd;
            font-weight: 700;
            background: transparent;
            border: none;
            cursor: pointer;
            text-align: left;
        }
        .sb-section:hover { color: #fff; background-color: rgba(30, 64, 175, 0.4); }

        .sb-chevron {
            transition: transform 0.2s;
            font-size: 0.7rem;
        }
        .sb-chevron.open { transform: rotate(90deg); }

        .sb-content {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease-out;
        }
        .sb-content.open {
            max-height: 2000px;
            transition: max-height 0.4s ease-in;
        }

        .sb-dot {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #93c5fd;
        }
        .sb-top, .sb-link { position: relative; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
<div class="flex min-h-screen">

    @php
        $user       = auth()->user();
        $isSuperAdmin = $user?->hasRole("super_admin");
        $isAdmin      = $user?->hasRole("admin");
        $isHead       = $user?->hasRole("head_of_school");
        $isAcademic   = $user?->hasRole("academic_master");
        $isBursar     = $user?->hasRole("bursar");
        $isTeacher    = $user?->hasRole("teacher");
        $isDuty       = $user?->hasRole("teacher_on_duty");
        $isParent     = $user?->hasRole("parent");
        $isStudent    = $user?->hasRole("student");

        $staff = \App\Models\Staff::where("user_id", $user?->id)->first();
        $isClassTeacher = $staff && \App\Models\ClassRoom::where("class_teacher_id", $staff->id)->exists();

        $currentRoute = request()->route()?->getName() ?? "";
    @endphp

    <aside class="w-64 bg-blue-900 text-white flex-shrink-0 overflow-y-auto">

        <div class="p-4 text-xl font-bold border-b border-blue-800">NEOVAM SMS</div>

        <nav class="py-2">

            {{-- ================= SUPER ADMIN ================= --}}
            @if($isSuperAdmin)
                <button type="button" onclick="toggleSection('super-admin')" class="sb-section">
                    <span>Super Admin</span>
                    <span class="sb-chevron" id="chev-super-admin">▶</span>
                </button>
                <div class="sb-content" id="sec-super-admin">
                    <a href="{{ route("super-admin.dashboard") }}" class="sb-top"><span class="sb-dot"></span>Network Dashboard</a>
                    <a href="{{ route("super-admin.schools.index") }}" class="sb-top"><span class="sb-dot"></span>All Schools</a>
                    <a href="{{ route("super-admin.schools.create") }}" class="sb-top"><span class="sb-dot"></span>Add School</a>
                    <a href="{{ route("super-admin.schools.compare") }}" class="sb-top"><span class="sb-dot"></span>Compare Schools</a>
                </div>
            @endif

            {{-- ================= ADMINISTRATION ================= --}}
            @if($isAdmin || $isHead || $isAcademic || $isBursar)
                <button type="button" onclick="toggleSection('admin')" class="sb-section">
                    <span>Administration</span>
                    <span class="sb-chevron" id="chev-admin">▶</span>
                </button>
                <div class="sb-content" id="sec-admin">
                    <a href="{{ route("admin.dashboard") }}" class="sb-top {{ $currentRoute === "admin.dashboard" ? "active" : "" }}"><span class="sb-dot"></span>Dashboard</a>
                    <a href="{{ route("admin.students.index") }}" class="sb-top {{ str_starts_with($currentRoute, "admin.students") ? "active" : "" }}"><span class="sb-dot"></span>Students</a>
                    <a href="{{ route("admin.staff.index") }}" class="sb-top"><span class="sb-dot"></span>Staff</a>
                    <a href="{{ route("admin.attendance.index") }}" class="sb-top"><span class="sb-dot"></span>Attendance</a>
                    <a href="{{ route("admin.results.index") }}" class="sb-top"><span class="sb-dot"></span>Results</a>
                    <a href="{{ route("admin.emergencies.index") }}" class="sb-top"><span class="sb-dot"></span>Emergencies</a>
                    <a href="{{ route("admin.communication.index") }}" class="sb-top"><span class="sb-dot"></span>Communication</a>
                    <a href="{{ route("admin.subjects.index") }}" class="sb-top"><span class="sb-dot"></span>Subjects</a>
                    <a href="{{ route("admin.duty-rosters.index") }}" class="sb-top"><span class="sb-dot"></span>Duty Rosters</a>
                    <a href="{{ route("admin.announcements.index") }}" class="sb-top"><span class="sb-dot"></span>Announcements</a>

                    <button type="button" onclick="toggleSection('admin-finance')" class="sb-section" style="padding-left: 1.5rem;">
                        <span>Finance</span>
                        <span class="sb-chevron" id="chev-admin-finance">▶</span>
                    </button>
                    <div class="sb-content" id="sec-admin-finance">
                        <a href="{{ route("admin.fee-structures.index") }}" class="sb-link"><span class="sb-dot"></span>Fee Structures</a>
                        <a href="{{ route("admin.invoices.index") }}" class="sb-link"><span class="sb-dot"></span>Invoices</a>
                        <a href="{{ route("admin.payments.index") }}" class="sb-link"><span class="sb-dot"></span>Payments</a>
                        <a href="{{ route("admin.income-tracking.index") }}" class="sb-link"><span class="sb-dot"></span>Income Tracking</a>
                        <a href="{{ route("admin.fee-reminders.index") }}" class="sb-link"><span class="sb-dot"></span>Fee Reminders</a>
                        <a href="{{ route("admin.payroll-runs.index") }}" class="sb-link"><span class="sb-dot"></span>Payroll</a>
                        <a href="{{ route("admin.salary-structures.index") }}" class="sb-link"><span class="sb-dot"></span>Salary Structures</a>
                    </div>

                    <a href="{{ route("admin.analytics.index") }}" class="sb-top"><span class="sb-dot"></span>Analytics</a>
                    <a href="{{ route("admin.exports.index") }}" class="sb-top"><span class="sb-dot"></span>Reports &amp; Exports</a>
                    <a href="{{ route("admin.performance-reviews.index") }}" class="sb-top"><span class="sb-dot"></span>Performance Reviews</a>
                    <a href="{{ route("admin.department-activities.index") }}" class="sb-top"><span class="sb-dot"></span>Department Activities</a>
                    <a href="{{ route("admin.staff-attendance.index") }}" class="sb-top"><span class="sb-dot"></span>Staff Attendance</a>
                    <a href="{{ route("admin.staff-transfers.index") }}" class="sb-top"><span class="sb-dot"></span>Staff Transfers</a>

                    @if($isAdmin)
                        <button type="button" onclick="toggleSection('admin-settings')" class="sb-section" style="padding-left: 1.5rem;">
                            <span>Settings</span>
                            <span class="sb-chevron" id="chev-admin-settings">▶</span>
                        </button>
                        <div class="sb-content" id="sec-admin-settings">
                            <a href="{{ route("admin.settings.school") }}" class="sb-link"><span class="sb-dot"></span>School Settings</a>
                            <a href="{{ route("admin.settings.sms") }}" class="sb-link"><span class="sb-dot"></span>SMS Settings</a>
                            <a href="{{ route("admin.sms-templates.index") }}" class="sb-link"><span class="sb-dot"></span>SMS Templates</a>
                            <a href="{{ route("admin.sms-delivery.index") }}" class="sb-link"><span class="sb-dot"></span>SMS Delivery</a>
                            <a href="{{ route("admin.sms-cost.index") }}" class="sb-link"><span class="sb-dot"></span>SMS Cost Tracking</a>
                            <a href="{{ route("admin.settings.pwa") }}" class="sb-link"><span class="sb-dot"></span>Mobile App</a>
                            <a href="{{ route("admin.backups.index") }}" class="sb-link"><span class="sb-dot"></span>Backups</a>
                        </div>
                    @endif
                </div>
            @endif

            {{-- ================= ACADEMIC ================= --}}
            @if($isAcademic || $isAdmin || $isHead)
                <button type="button" onclick="toggleSection('academic')" class="sb-section">
                    <span>Academic</span>
                    <span class="sb-chevron" id="chev-academic">▶</span>
                </button>
                <div class="sb-content" id="sec-academic">
                    <a href="{{ route("academic.dashboard") }}" class="sb-top"><span class="sb-dot"></span>Academic Dashboard</a>
                    <a href="{{ route("academic.syllabus") }}" class="sb-top"><span class="sb-dot"></span>Syllabus</a>
                    <a href="{{ route("academic.timetable") }}" class="sb-top"><span class="sb-dot"></span>Timetable</a>
                    <a href="{{ route("academic.exams") }}" class="sb-top"><span class="sb-dot"></span>Exams</a>
                    <a href="{{ route("academic.results") }}" class="sb-top"><span class="sb-dot"></span>Results</a>
                    <a href="{{ route("academic.report-cards.index") }}" class="sb-top"><span class="sb-dot"></span>Report Cards</a>
                    <a href="{{ route("academic.teachers") }}" class="sb-top"><span class="sb-dot"></span>Teachers</a>
                    <a href="{{ route("academic.performance") }}" class="sb-top"><span class="sb-dot"></span>Performance</a>
                    <a href="{{ route("academic.promotions") }}" class="sb-top"><span class="sb-dot"></span>Auto-Promotion</a>
                    <a href="{{ route("academic.calendar") }}" class="sb-top"><span class="sb-dot"></span>Calendar</a>
                    <a href="{{ route("academic.meetings") }}" class="sb-top"><span class="sb-dot"></span>Meetings</a>
                </div>
            @endif

            {{-- ================= TEACHER ================= --}}
            @if($isTeacher || $isDuty)
                <button type="button" onclick="toggleSection('teacher')" class="sb-section">
                    <span>My Workspace</span>
                    <span class="sb-chevron" id="chev-teacher">▶</span>
                </button>
                <div class="sb-content" id="sec-teacher">
                    <a href="{{ route("teacher.dashboard") }}" class="sb-top"><span class="sb-dot"></span>Dashboard</a>
                    <a href="{{ route("teacher.timetable") }}" class="sb-top"><span class="sb-dot"></span>My Timetable</a>
                    <a href="{{ route("teacher.classes") }}" class="sb-top"><span class="sb-dot"></span>My Classes</a>
                    <a href="{{ route("teacher.students") }}" class="sb-top"><span class="sb-dot"></span>My Students</a>
                    <a href="{{ route("teacher.attendance") }}" class="sb-top"><span class="sb-dot"></span>Mark Attendance</a>
                    <a href="{{ route("teacher.lesson-plans") }}" class="sb-top"><span class="sb-dot"></span>Lesson Plans</a>
                    <a href="{{ route("teacher.schemes") }}" class="sb-top"><span class="sb-dot"></span>Schemes</a>
                    <a href="{{ route("teacher.incidents") }}" class="sb-top"><span class="sb-dot"></span>Incidents</a>
                    <a href="{{ route("teacher.duty") }}" class="sb-top"><span class="sb-dot"></span>Duty Roster</a>
                    <a href="{{ route("teacher.department") }}" class="sb-top"><span class="sb-dot"></span>Department Tasks</a>
                    <a href="{{ route("teacher.parents") }}" class="sb-top"><span class="sb-dot"></span>Parent Contacts</a>
                    <a href="{{ route("teacher.reports") }}" class="sb-top"><span class="sb-dot"></span>My Reports</a>
                    <a href="{{ route("teacher.profile") }}" class="sb-top"><span class="sb-dot"></span>Profile</a>
                    <a href="{{ route("teacher.leave") }}" class="sb-top"><span class="sb-dot"></span>Leave Requests</a>
                </div>
            @endif

            {{-- ================= CLASS TEACHER ================= --}}
            @if($isClassTeacher)
                <button type="button" onclick="toggleSection('class-teacher')" class="sb-section">
                    <span>Class Teacher</span>
                    <span class="sb-chevron" id="chev-class-teacher">▶</span>
                </button>
                <div class="sb-content" id="sec-class-teacher">
                    <a href="{{ route("class-teacher.dashboard") }}" class="sb-top"><span class="sb-dot"></span>Class Dashboard</a>
                    <a href="{{ route("class-teacher.students") }}" class="sb-top"><span class="sb-dot"></span>My Class Students</a>
                    <a href="{{ route("class-teacher.attendance") }}" class="sb-top"><span class="sb-dot"></span>Class Attendance</a>
                    <a href="{{ route("class-teacher.discipline") }}" class="sb-top"><span class="sb-dot"></span>Discipline</a>
                    <a href="{{ route("class-teacher.performance") }}" class="sb-top"><span class="sb-dot"></span>Performance</a>
                    <a href="{{ route("class-teacher.welfare") }}" class="sb-top"><span class="sb-dot"></span>Welfare</a>
                    <a href="{{ route("class-teacher.parents") }}" class="sb-top"><span class="sb-dot"></span>Parent Contacts</a>
                    <a href="{{ route("class-teacher.meetings") }}" class="sb-top"><span class="sb-dot"></span>Class Meetings</a>
                </div>
            @endif

            {{-- ================= TEACHER ON DUTY ================= --}}
            @if($isDuty)
                <button type="button" onclick="toggleSection('duty')" class="sb-section">
                    <span>Teacher on Duty</span>
                    <span class="sb-chevron" id="chev-duty">▶</span>
                </button>
                <div class="sb-content" id="sec-duty">
                    <a href="{{ route("duty.dashboard") }}" class="sb-top"><span class="sb-dot"></span>Duty Dashboard</a>
                    <a href="{{ route("duty.schedule") }}" class="sb-top"><span class="sb-dot"></span>My Schedule</a>
                    <a href="{{ route("duty.supervision") }}" class="sb-top"><span class="sb-dot"></span>Supervision Logs</a>
                    <a href="{{ route("duty.reports") }}" class="sb-top"><span class="sb-dot"></span>Duty Reports</a>
                    <a href="{{ route("duty.handovers") }}" class="sb-top"><span class="sb-dot"></span>Handover Notes</a>
                </div>
            @endif

            {{-- ================= SHARED ================= --}}
            @if($isTeacher || $isDuty || $isAdmin || $isHead || $isAcademic)
                <button type="button" onclick="toggleSection('shared')" class="sb-section">
                    <span>Shared</span>
                    <span class="sb-chevron" id="chev-shared">▶</span>
                </button>
                <div class="sb-content" id="sec-shared">
                    <a href="{{ route("shared.dashboard") }}" class="sb-top"><span class="sb-dot"></span>Staff Dashboard</a>
                    <a href="{{ route("shared.my-attendance") }}" class="sb-top"><span class="sb-dot"></span>My Attendance</a>
                    <a href="{{ route("shared.class-teachers") }}" class="sb-top"><span class="sb-dot"></span>Class Teachers</a>
                    <a href="{{ route("shared.classes") }}" class="sb-top"><span class="sb-dot"></span>All Classes</a>
                    <a href="{{ route("shared.attendance") }}" class="sb-top"><span class="sb-dot"></span>Attendance Overview</a>
                    <a href="{{ route("shared.duty-roster") }}" class="sb-top"><span class="sb-dot"></span>Public Duty Roster</a>
                    <a href="{{ route("shared.announcements") }}" class="sb-top"><span class="sb-dot"></span>Announcements</a>
                    <a href="{{ route("shared.directory") }}" class="sb-top"><span class="sb-dot"></span>Staff Directory</a>
                </div>
            @endif

            {{-- ================= PARENT ================= --}}
            @if($isParent)
                <button type="button" onclick="toggleSection('parent')" class="sb-section">
                    <span>Parent Portal</span>
                    <span class="sb-chevron" id="chev-parent">▶</span>
                </button>
                <div class="sb-content" id="sec-parent">
                    <a href="{{ route("parent.dashboard") }}" class="sb-top"><span class="sb-dot"></span>Dashboard</a>
                    <a href="{{ route("parent.results") }}" class="sb-top"><span class="sb-dot"></span>Children Results</a>
                    <a href="{{ route("parent.attendance") }}" class="sb-top"><span class="sb-dot"></span>Children Attendance</a>
                    <a href="{{ route("parent.promotions") }}" class="sb-top"><span class="sb-dot"></span>Promotions</a>
                    <a href="{{ route("parent.timetable") }}" class="sb-top"><span class="sb-dot"></span>Timetable</a>
                    <a href="{{ route("parent.fees") }}" class="sb-top"><span class="sb-dot"></span>Fees &amp; Invoices</a>
                    <a href="{{ route("parent.payments") }}" class="sb-top"><span class="sb-dot"></span>Payments</a>
                    <a href="{{ route("parent.incidents") }}" class="sb-top"><span class="sb-dot"></span>Incidents</a>
                    <a href="{{ route("parent.announcements") }}" class="sb-top"><span class="sb-dot"></span>Announcements</a>
                </div>
            @endif

            {{-- ================= STUDENT ================= --}}
            @if($isStudent)
                <button type="button" onclick="toggleSection('student')" class="sb-section">
                    <span>Student Portal</span>
                    <span class="sb-chevron" id="chev-student">▶</span>
                </button>
                <div class="sb-content" id="sec-student">
                    <a href="{{ route("student.dashboard") }}" class="sb-top"><span class="sb-dot"></span>Dashboard</a>
                    <a href="{{ route("student.results") }}" class="sb-top"><span class="sb-dot"></span>My Results</a>
                    <a href="{{ route("student.attendance") }}" class="sb-top"><span class="sb-dot"></span>My Attendance</a>
                </div>
            @endif

        </nav>

        <div class="mt-4 border-t border-blue-800 p-4 text-sm text-blue-200">
            <div class="font-semibold text-white">{{ $user?->name ?? "Guest" }}</div>
            <div class="text-xs text-blue-300">{{ $user?->getRoleNames()->first() ?? "" }}</div>
            <form method="POST" action="{{ route("logout") }}" class="mt-2">
                @csrf
                <button class="text-xs underline hover:text-white">Logout</button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-6 overflow-y-auto">
        @if(session("success"))
            <div class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 mb-4 rounded">{{ session("success") }}</div>
        @endif
        @if(session("error"))
            <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 mb-4 rounded">{{ session("error") }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 mb-4 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        @yield("content")
    </main>
</div>

<script>
    // ============ SIDEBAR SECTION TOGGLE ============
    function toggleSection(key) {
        const content = document.getElementById("sec-" + key);
        const chevron = document.getElementById("chev-" + key);
        if (!content || !chevron) return;

        const isOpen = content.classList.contains("open");

        if (isOpen) {
            content.classList.remove("open");
            chevron.classList.remove("open");
        } else {
            content.classList.add("open");
            chevron.classList.add("open");
        }

        // Save state
        const states = JSON.parse(localStorage.getItem("neovam-sidebar") || "{}");
        states[key] = !isOpen;
        localStorage.setItem("neovam-sidebar", JSON.stringify(states));
    }

    // ============ RESTORE STATE ON PAGE LOAD ============
    (function () {
        const states = JSON.parse(localStorage.getItem("neovam-sidebar") || "{}");

        // Auto-open the section that contains the current route
        const currentRoute = "{{ request()->route()?->getName() ?? "" }}";

        const sectionMap = {
            "super-admin":     ["super-admin."],
            "admin":           ["admin."],
            "academic":        ["academic."],
            "teacher":         ["teacher."],
            "class-teacher":   ["class-teacher."],
            "duty":            ["duty."],
            "shared":          ["shared."],
            "parent":          ["parent."],
            "student":         ["student."],
        };

        // Determine which top-level section should be open
        let activeSection = null;
        for (const [section, prefixes] of Object.entries(sectionMap)) {
            if (prefixes.some(p => currentRoute.startsWith(p))) {
                activeSection = section;
                break;
            }
        }

        // Apply saved state; if none saved, only open active section
        Object.keys(sectionMap).forEach(key => {
            const content = document.getElementById("sec-" + key);
            const chevron = document.getElementById("chev-" + key);
            if (!content || !chevron) return;

            const saved = states[key];
            const isActive = key === activeSection;

            const open = (saved !== undefined) ? saved : isActive;

            if (open) {
                content.classList.add("open");
                chevron.classList.add("open");
            } else {
                content.classList.remove("open");
                chevron.classList.remove("open");
            }
        });

        // Handle sub-sections (finance, settings) — restore saved state
        ["admin-finance", "admin-settings"].forEach(key => {
            const content = document.getElementById("sec-" + key);
            const chevron = document.getElementById("chev-" + key);
            if (!content || !chevron) return;

            const saved = states[key];
            if (saved === true) {
                content.classList.add("open");
                chevron.classList.add("open");
            }
        });
    })();
</script>

</body>
</html>