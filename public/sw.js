// NEOVAM SMS — Service Worker
// Handles offline caching + install prompt

const CACHE_NAME = "neovam-sms-v1";
const OFFLINE_URL = "/offline";

// Static assets to cache on install
const PRECACHE_URLS = [
    "/",
    "/login",
    "/offline",
    "/manifest.json",
    "/logo.png",
    "/build/manifest.json"
];

// Install: cache initial assets
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_URLS).catch(() => {
                // Ignore individual failures
            });
        })
    );
    self.skipWaiting();
});

// Activate: clean old caches
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((names) => {
            return Promise.all(
                names.filter(n => n !== CACHE_NAME).map(n => caches.delete(n))
            );
        })
    );
    self.clients.claim();
});

// Fetch: network-first, fallback to cache
self.addEventListener("fetch", (event) => {
    const { request } = event;

    // Skip non-GET and external requests
    if (request.method !== "GET") return;
    if (!request.url.startsWith(self.location.origin)) return;

    // Skip admin POST-like paths and API
    if (request.url.includes("/api/")) return;

    event.respondWith(
        fetch(request)
            .then((response) => {
                // Cache successful HTML/CSS/JS
                if (response.status === 200 &&
                    (request.destination === "document" ||
                     request.destination === "style" ||
                     request.destination === "script" ||
                     request.destination === "image")) {
                    const copy = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                }
                return response;
            })
            .catch(() => {
                // Offline fallback
                return caches.match(request).then((cached) => {
                    if (cached) return cached;
                    if (request.mode === "navigate") {
                        return caches.match(OFFLINE_URL);
                    }
                });
            })
    );
});

// Listen for skipWaiting message from the app
self.addEventListener("message", (event) => {
    if (event.data === "SKIP_WAITING") {
        self.skipWaiting();
    }
});