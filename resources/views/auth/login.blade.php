<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in — NEOVAM SMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 flex flex-col">

    <!-- HEADER -->
    <header class="w-full">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ url("/") }}" class="flex items-center gap-3">
                <img src="{{ asset("logo.png") }}" alt="NEOVAM" class="h-9 w-auto" onerror="this.style.display='none'">
                <span class="text-white font-bold text-lg">NEOVAM SMS</span>
            </a>
            <a href="{{ url("/") }}" class="text-blue-100 text-sm hover:text-white">Back to home</a>
        </div>
    </header>

    <!-- LOGIN CARD -->
    <main class="flex-1 flex items-center justify-center px-6 py-10">
        <div class="w-full max-w-md">

            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

                <!-- CARD HEADER -->
                <div class="bg-blue-900 px-8 py-6 text-center">
                    <img src="{{ asset("logo.png") }}" alt="NEOVAM" class="h-14 w-auto mx-auto mb-3" onerror="this.style.display='none'">
                    <h1 class="text-white text-2xl font-bold">NEOVAM SMS</h1>
                    <p class="text-blue-200 text-sm mt-1">School Management System</p>
                </div>

                <!-- CARD BODY -->
                <div class="px-8 py-8">

                    <h2 class="text-xl font-bold text-blue-900 mb-1">Welcome back</h2>
                    <p class="text-sm text-gray-500 mb-6">Log in to continue to your dashboard.</p>

                    @if(session("status"))
                        <div class="bg-green-100 border-l-4 border-green-600 text-green-800 p-3 mb-4 rounded text-sm">
                            {{ session("status") }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route("login") }}" class="space-y-4">
                        @csrf

                        <!-- EMAIL -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old("email") }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="you@school.co.tz"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-blue-900 focus:ring-2 focus:ring-blue-200 outline-none">
                            @error("email")
                                <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- PASSWORD -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter your password"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:border-blue-900 focus:ring-2 focus:ring-blue-200 outline-none">
                            @error("password")
                                <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- REMEMBER + FORGOT -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input id="remember_me" type="checkbox" name="remember"
                                       class="rounded border-gray-300 text-blue-900 focus:ring-blue-200">
                                <span>Remember me</span>
                            </label>

                            @if (Route::has("password.request"))
                                <a href="{{ route("password.request") }}" class="text-sm text-blue-900 hover:underline">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- SUBMIT -->
                        <button type="submit"
                                class="w-full bg-blue-900 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-800 transition">
                            Log in
                        </button>
                    </form>

                    <div class="mt-6 text-center text-xs text-gray-400">
                        Accounts are created by the school administrator.
                    </div>
                </div>
            </div>

            <!-- BELOW CARD -->
            <div class="text-center mt-6 text-blue-100 text-xs">
                &copy; {{ date("Y") }} NEOVAM TECHNOLOGIES LTD
            </div>
        </div>
    </main>

</body>
</html>