<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password — NEOVAM SMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 to-blue-700 flex items-center justify-center p-6">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full">
        <div class="text-center mb-6">
            <img src="{{ asset("logo.png") }}" alt="NEOVAM" class="h-16 mx-auto mb-3" onerror="this.style.display='none'">
            <h1 class="text-2xl font-bold text-blue-900">Change Password</h1>
            <p class="text-sm text-gray-600 mt-1">SUBChange Password</p>
        </div>
        @if(session("error"))
            <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-3 mb-4 rounded text-sm">{{ session("error") }}</div>
        @endif
        @if(session("success"))
            <div class="bg-green-100 border-l-4 border-green-600 text-green-800 p-3 mb-4 rounded text-sm">{{ session("success") }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-3 mb-4 rounded text-sm">
                <ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route("password.change.update") }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Current Password</label>
                <input type="password" name="current_password" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">New Password</label>
                <input type="password" name="password" required minlength="8"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
                <p class="text-xs text-gray-500 mt-1">Minimum 8 characters.</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
            </div>
            <button type="submit" class="w-full bg-blue-900 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-800">
                Update Password
            </button>
        </form>
    </div>
</body>
</html>