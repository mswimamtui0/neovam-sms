<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password — NEOVAM SMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 to-blue-700 flex items-center justify-center p-6">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full">
        <div class="text-center mb-6">
            <img src="{{ asset("logo.png") }}" alt="NEOVAM" class="h-16 mx-auto mb-3" onerror="this.style.display='none'">
            <h1 class="text-2xl font-bold text-blue-900">Forgot Password</h1>
            <p class="text-sm text-gray-600 mt-1">SUBForgot Password</p>
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
        <form method="POST" action="{{ route("password.otp.send") }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Phone or Email</label>
                <input type="text" name="identifier" value="{{ old("identifier") }}" required
                       placeholder="+255712345678 or you@example.com"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:border-blue-900 focus:ring-2 focus:ring-blue-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Receive code via</label>
                <select name="channel" class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
                    <option value="sms">SMS (phone)</option>
                    <option value="email">Email</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-900 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-800">
                Send Code
            </button>
            <div class="text-center">
                <a href="{{ route("login") }}" class="text-sm text-blue-700 hover:underline">Back to login</a>
            </div>
        </form>
    </div>
</body>
</html>