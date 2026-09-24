<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield("title", "NEOVAM SMS")</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sb-link {
            display: block;
            padding: 0.4rem 0.75rem 0.4rem 1.75rem;
            border-radius: 0.25rem;
            color: #dbeafe;
            text-decoration: none;
            font-size: 0.8rem;
        }
        .sb-link:hover { background-color: #1e40af; color: #fff; }
        .sb-link.active { background-color: #1e40af; color: #fff; font-weight: 600; }
        .sb-group {
            padding: 0.75rem 1rem 0.25rem;
            font-size: 0.65rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #93c5fd;
            font-weight: 700;
        }
        .sb-top {
            display: block;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            color: #fff;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .sb-top:hover { background-color: #1e40af; }
        .sb-top.active { background-color: #1e40af; font-weight: 600; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
<div class="flex min-h-screen">

    @php
        $user          = auth()->user();
        $isAdmin       = $user?->hasRole("admin");
        $isHead        = $user?->hasRole("head_of_school");
        $isAcademic    = $user?->hasRole("academic_master");
        $isBursar      = $user?->hasRole("bursar");
        $isTeacher     = $user?->hasRole("teacher");
        $isDuty        = $user?->hasRole("teacher_on_duty");
        $isParent      = $user?->hasRole("parent");
        $isStudent     = $user?->hasRole("student");

        // Is this user a class teacher of any class?
        $staff = \App\Models\Staff::where("user_id", $user?->id)->first();
        $isClassTeacher = $staff && \App\Models\ClassRoom::where("class_teacher_id", $staff->id)->exists();

        $currentRoute = request()->route()?->getName() ?? "";
    @endphp

    <aside class="w-64 bg-blue-900 text-white flex-shrink-0 overflow-y-auto">

        <div class="p-4 text-xl font-bold border-b border-blue-800">NEOVAM SMS</div>

        <nav class="py-2">

            {{-- ================= ADMIN ================= --}}
            @if($isAdmin || $isHead || $isAcademic || $isBursar)
                <div class="sb-group">Administration</div>
                <a href="{{ route("admin.dashboard") }}" class="sb-top {{ str_starts_with($currentRoute, "admin.dashboard") ? "active" : "" }}">Dashboard</a>
                <a href="{{ route("admin.students.index") }}" class="sb-top">Students</a>
                <a href="{{ route("admin.staff.index") }}" class="sb-top">Staff</a>
                <a href="{{ route("admin.attendance.index") }}" class="sb-top">Attendance</a>
                <a href="{{ route("admin.results.index") }}" class="sb-top">Results</a>
                <a href="{{ route("admin.emergencies.index") }}" class="sb-top">Emergencies</a>
                <a href="{{ route("admin.communication.index") }}" class="sb-top">Communication</a>
                <a href="{{ route("admin.subjects.index") }}" class="sb-top">Subjects</a>
                <a href="{{ route("admin.duty-rosters.index") }}" class="sb-top">Duty Rosters</a>
                <a href="{{ route("admin.announcements.index") }}" class="sb-top">Announcements</a>
                @if($isAdmin)
                    <a href="{{ route("admin.settings.school") }}" class="sb-top">School Settings</a>
                @endif
            @endif

            {{-- ================= ACADEMIC ================= --}}
            @if($isAcademic || $isAdmin || $isHead)
                <div class="sb-group">Academic</div>
                <a href="{{ route("academic.dashboard") }}" class="sb-top">Academic Dashboard</a>
                <a href="{{ route("academic.syllabus") }}" class="sb-top">Syllabus</a>
                <a href="{{ route("academic.timetable") }}" class="sb-top">Timetable</a>
                <a href="{{ route("academic.exams") }}" class="sb-top">Exams</a>
                <a href="{{ route("academic.results") }}" class="sb-top">Results</a>
                <a href="{{ route("academic.teachers") }}" class="sb-top">Teachers</a>
                <a href="{{ route("academic.performance") }}" class="sb-top">Performance</a>
                <a href="{{ route("academic.calendar") }}" class="sb-top">Calendar</a>
                <a href="{{ route("academic.meetings") }}" class="sb-top">Meetings</a>
            @endif

            {{-- ================= TEACHER ================= --}}
            @if($isTeacher || $isDuty)
                <div class="sb-group">My Workspace</div>
                <a href="{{ route("teacher.dashboard") }}" class="sb-link">Dashboard</a>
                <a href="{{ route("teacher.timetable") }}" class="sb-link">My Timetable</a>
                <a href="{{ route("teacher.classes") }}" class="sb-link">My Classes</a>
                <a href="{{ route("teacher.students") }}" class="sb-link">My Students</a>
                <a href="{{ route("teacher.attendance") }}" class="sb-link">Mark Attendance</a>

                <div class="sb-group">Teaching</div>
                <a href="{{ route("teacher.lesson-plans") }}" class="sb-link">Lesson Plans</a>
                <a href="{{ route("teacher.schemes") }}" class="sb-link">Schemes of Work</a>

                <div class="sb-group">Daily Duties</div>
                <a href="{{ route("teacher.incidents") }}" class="sb-link">Incidents</a>
                <a href="{{ route("teacher.duty") }}" class="sb-link">My Duty Roster</a>
                <a href="{{ route("teacher.department") }}" class="sb-link">Department Tasks</a>

                <div class="sb-group">Communication</div>
                <a href="{{ route("teacher.parents") }}" class="sb-link">Parent Contacts</a>
                <a href="{{ route("teacher.reports") }}" class="sb-link">My Reports</a>

                <div class="sb-group">Personal</div>
                <a href="{{ route("teacher.profile") }}" class="sb-link">My Profile</a>
                <a href="{{ route("teacher.leave") }}" class="sb-link">Leave Requests</a>
            @endif

            {{-- ================= CLASS TEACHER ================= --}}
            @if($isClassTeacher)
                <div class="sb-group">Class Teacher</div>
                <a href="{{ route("class-teacher.dashboard") }}" class="sb-link">Class Dashboard</a>
                <a href="{{ route("class-teacher.students") }}" class="sb-link">My Class Students</a>
                <a href="{{ route("class-teacher.attendance") }}" class="sb-link">Class Attendance</a>
                <a href="{{ route("class-teacher.discipline") }}" class="sb-link">Discipline</a>
                <a href="{{ route("class-teacher.performance") }}" class="sb-link">Performance</a>
                <a href="{{ route("class-teacher.welfare") }}" class="sb-link">Welfare</a>
                <a href="{{ route("class-teacher.parents") }}" class="sb-link">Parent Contacts</a>
                <a href="{{ route("class-teacher.meetings") }}" class="sb-link">Class Meetings</a>
            @endif

            {{-- ================= TEACHER ON DUTY ================= --}}
            @if($isDuty)
                <div class="sb-group">Teacher on Duty</div>
                <a href="{{ route("duty.dashboard") }}" class="sb-link">Duty Dashboard</a>
                <a href="{{ route("duty.schedule") }}" class="sb-link">My Duty Schedule</a>
                <a href="{{ route("duty.supervision") }}" class="sb-link">Supervision Logs</a>
                <a href="{{ route("duty.reports") }}" class="sb-link">Duty Reports</a>
                <a href="{{ route("duty.handovers") }}" class="sb-link">Handover Notes</a>
            @endif

            {{-- ================= SHARED STAFF ================= --}}
            @if($isTeacher || $isDuty || $isAdmin || $isHead || $isAcademic)
                <div class="sb-group">Shared</div>
                <a href="{{ route("shared.dashboard") }}" class="sb-link">Staff Dashboard</a>
                <a href="{{ route("shared.class-teachers") }}" class="sb-link">Class Teachers</a>
                <a href="{{ route("shared.classes") }}" class="sb-link">All Classes</a>
                <a href="{{ route("shared.attendance") }}" class="sb-link">Attendance Overview</a>
                <a href="{{ route("shared.duty-roster") }}" class="sb-link">Public Duty Roster</a>
                <a href="{{ route("shared.announcements") }}" class="sb-link">Announcements</a>
                <a href="{{ route("shared.directory") }}" class="sb-link">Staff Directory</a>
            @endif

            {{-- ================= PARENT ================= --}}
            @if($isParent)
                <div class="sb-group">Parent</div>
                <a href="{{ route("parent.dashboard") }}" class="sb-top">Dashboard</a>
                <a href="{{ route("parent.results") }}" class="sb-top">Children Results</a>
                <a href="{{ route("parent.attendance") }}" class="sb-top">Children Attendance</a>
                <a href="{{ route("parent.incidents") }}" class="sb-top">Incidents</a>
            @endif

            {{-- ================= STUDENT ================= --}}
            @if($isStudent)
                <div class="sb-group">Student</div>
                <a href="{{ route("student.dashboard") }}" class="sb-top">Dashboard</a>
                <a href="{{ route("student.results") }}" class="sb-top">My Results</a>
                <a href="{{ route("student.attendance") }}" class="sb-top">My Attendance</a>
            @endif

        </nav>

        {{-- ================= USER FOOTER ================= --}}
        <div class="mt-auto border-t border-blue-800 p-4 text-sm text-blue-200">
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
</body>
</html>