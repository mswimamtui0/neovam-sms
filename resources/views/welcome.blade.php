<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEOVAM SMS — School Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- NAVBAR -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset("logo.png") }}" alt="NEOVAM" class="h-10 w-auto" onerror="this.style.display='none'">
                <span class="text-xl font-bold text-blue-900">NEOVAM SMS</span>
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route("dashboard") }}" class="text-blue-900 font-semibold hover:underline">Go to Dashboard</a>
                @else
                    <a href="{{ route("login") }}" class="bg-blue-900 text-white px-5 py-2 rounded hover:bg-blue-800">Log in</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="bg-gradient-to-br from-blue-900 to-blue-700 text-white">
        <div class="max-w-7xl mx-auto px-6 py-20 grid grid-cols-2 gap-10 items-center">
            <div>
                <h1 class="text-5xl font-bold mb-4 leading-tight">School Management System</h1>
                <p class="text-xl text-blue-100 mb-8">
                    Connecting Schools. Informing Parents. Empowering Education.
                </p>
                <p class="text-blue-200 mb-10">
                    A unified platform for Primary, Secondary, and A-Level schools — with automated SMS to parents for attendance, results, emergencies, and more.
                </p>

                <div class="flex gap-4">
                    <a href="{{ route("login") }}" class="bg-white text-blue-900 px-8 py-3 rounded font-semibold hover:bg-blue-50">
                        Log in
                    </a>
                    <a href="#features" class="border border-white text-white px-8 py-3 rounded font-semibold hover:bg-blue-800">
                        Learn More
                    </a>
                </div>
            </div>
            <div class="flex justify-center">
                <img src="{{ asset("logo.png") }}" alt="NEOVAM" class="max-w-sm w-full" onerror="this.style.display='none'">
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-blue-900 text-center mb-3">Everything Your School Needs</h2>
            <p class="text-center text-gray-600 mb-12">One system. Every department. Every parent connected.</p>

            <div class="grid grid-cols-3 gap-6">
                <div class="border rounded-lg p-6 hover:shadow-lg transition">
                    <div class="text-2xl font-bold text-blue-900 mb-2">Student Management</div>
                    <p class="text-gray-600">Admissions at any class level, progression from Primary to A-Level, and complete student records.</p>
                </div>
                <div class="border rounded-lg p-6 hover:shadow-lg transition">
                    <div class="text-2xl font-bold text-blue-900 mb-2">Staff Management</div>
                    <p class="text-gray-600">Teachers, departments, subjects, duty rosters, class teachers, and academic masters.</p>
                </div>
                <div class="border rounded-lg p-6 hover:shadow-lg transition">
                    <div class="text-2xl font-bold text-blue-900 mb-2">Attendance</div>
                    <p class="text-gray-600">Daily and per-period attendance with instant SMS to parents when a student is absent.</p>
                </div>
                <div class="border rounded-lg p-6 hover:shadow-lg transition">
                    <div class="text-2xl font-bold text-blue-900 mb-2">Results &amp; Exams</div>
                    <p class="text-gray-600">Create exams, enter marks, publish results — parents receive automatic SMS summaries.</p>
                </div>
                <div class="border rounded-lg p-6 hover:shadow-lg transition">
                    <div class="text-2xl font-bold text-blue-900 mb-2">Emergency Alerts</div>
                    <p class="text-gray-600">Instant SMS to parents when an incident happens — sickness, accident, or discipline.</p>
                </div>
                <div class="border rounded-lg p-6 hover:shadow-lg transition">
                    <div class="text-2xl font-bold text-blue-900 mb-2">Bulk SMS</div>
                    <p class="text-gray-600">Send targeted SMS to parents by level, class, stream, or custom groups.</p>
                </div>
                <div class="border rounded-lg p-6 hover:shadow-lg transition">
                    <div class="text-2xl font-bold text-blue-900 mb-2">Timetable</div>
                    <p class="text-gray-600">Build weekly schedules for every teacher — from Monday to Sunday, morning to night.</p>
                </div>
                <div class="border rounded-lg p-6 hover:shadow-lg transition">
                    <div class="text-2xl font-bold text-blue-900 mb-2">Parent Portal</div>
                    <p class="text-gray-600">Parents log in to view their child's results, attendance, and school updates.</p>
                </div>
                <div class="border rounded-lg p-6 hover:shadow-lg transition">
                    <div class="text-2xl font-bold text-blue-900 mb-2">Multi-Level</div>
                    <p class="text-gray-600">Enable only the levels your school offers — Primary, Secondary, A-Level, or all.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-blue-900 text-white py-16">
        <div class="max-w-3xl mx-auto text-center px-6">
            <h2 class="text-3xl font-bold mb-4">Ready to get started?</h2>
            <p class="text-blue-100 mb-8">Log in to manage your school, teachers, students, and parent communication — all in one place.</p>
            <a href="{{ route("login") }}" class="bg-white text-blue-900 px-8 py-3 rounded font-semibold hover:bg-blue-50">
                Log in to your account
            </a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-gray-300 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="text-lg font-bold text-white mb-2">NEOVAM TECHNOLOGIES LTD</div>
            <div class="text-sm">Connecting Schools. Informing Parents. Empowering Education.</div>
            <div class="text-xs mt-3 text-gray-500">&copy; {{ date("Y") }} NEOVAM TECHNOLOGIES LTD. All rights reserved.</div>
        </div>
    </footer>

</body>
</html>