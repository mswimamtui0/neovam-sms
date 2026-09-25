<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Offline — NEOVAM SMS</title>
 <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 to-blue-700 flex items-center justify-center p-6">
 <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md text-center">
 <img src="/logo.png" alt="NEOVAM" class="h-16 mx-auto mb-4" onerror="this.style.display='none'">
 <h1 class="text-2xl font-bold text-blue-900 mb-2">You're Offline</h1>
 <p class="text-gray-600 mb-6">No internet connection detected. Some features are unavailable until you reconnect.</p>
 <button onclick="location.reload()" class="bg-blue-900 text-white px-6 py-2 rounded-lg">Try Again</button>
 </div>
</body>
</html>