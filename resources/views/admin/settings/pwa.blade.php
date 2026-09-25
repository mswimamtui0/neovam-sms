@extends("layouts.app")
@section("title", "Mobile App Settings")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Mobile App (PWA)</h1>

    <div class="grid grid-cols-2 gap-6">

        <div class="bg-white rounded shadow p-6">
            <h2 class="font-bold text-blue-900 mb-3">How to Install</h2>
            <ol class="list-decimal pl-5 space-y-2 text-sm">
                <li>Open this site on your phone's browser (Chrome / Safari)</li>
                <li>Tap the menu (three dots on Android, share icon on iOS)</li>
                <li>Choose <strong>"Add to Home Screen"</strong> / <strong>"Install App"</strong></li>
                <li>The NEOVAM SMS icon appears on your home screen</li>
                <li>Open it like a normal app — no browser chrome</li>
            </ol>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="font-bold text-blue-900 mb-3">Features</h2>
            <ul class="list-disc pl-5 space-y-1 text-sm">
                <li>Works on Android, iOS, and Desktop</li>
                <li>Fast launch from home screen</li>
                <li>Offline support for cached pages</li>
                <li>Full-screen — looks like a native app</li>
                <li>Automatic updates on new deploy</li>
                <li>No Play Store / App Store required</li>
            </ul>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="font-bold text-blue-900 mb-3">Manifest</h2>
            <p class="text-sm text-gray-600 mb-3">Served at <code class="bg-gray-100 px-2 py-0.5 rounded">/manifest.json</code></p>
            <a href="/manifest.json" target="_blank" class="bg-blue-900 text-white px-4 py-2 rounded text-sm">View Manifest</a>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="font-bold text-blue-900 mb-3">Service Worker</h2>
            <p class="text-sm text-gray-600 mb-3">Served at <code class="bg-gray-100 px-2 py-0.5 rounded">/sw.js</code></p>
            <a href="/sw.js" target="_blank" class="bg-blue-900 text-white px-4 py-2 rounded text-sm">View Service Worker</a>
        </div>

    </div>
@endsection