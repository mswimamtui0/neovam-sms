<div id="pwa-prompt" class="hidden fixed bottom-4 left-4 right-4 md:left-auto md:right-4 md:w-96 bg-white rounded-lg shadow-2xl border-2 border-blue-900 p-4 z-50">
 <div class="flex items-start gap-3">
 <img src="/logo.png" alt="NEOVAM" class="h-12 w-12 rounded" onerror="this.style.display='none'">
 <div class="flex-1">
 <h3 class="font-bold text-blue-900 mb-1">Install NEOVAM SMS</h3>
 <p class="text-sm text-gray-600 mb-3">Add to your home screen for faster access and offline support.</p>
 <div class="flex gap-2">
 <button id="pwa-install-btn" class="bg-blue-900 text-white px-4 py-2 rounded text-sm">Install</button>
 <button id="pwa-dismiss-btn" class="text-gray-600 text-sm underline">Not now</button>
 </div>
 </div>
 </div>
</div>

<script>
 (function () {
 let deferredPrompt;
 const prompt = document.getElementById("pwa-prompt");
 const installBtn = document.getElementById("pwa-install-btn");
 const dismissBtn = document.getElementById("pwa-dismiss-btn");

 // Already installed or dismissed?
 if (localStorage.getItem("pwa-dismissed") === "1") return;
 if (window.matchMedia("(display-mode: standalone)").matches) return;

 window.addEventListener("beforeinstallprompt", function (e) {
 e.preventDefault();
 deferredPrompt = e;
 setTimeout(() =>prompt.classList.remove("hidden"), 3000);
 });

 installBtn?.addEventListener("click", async function () {
 if (!deferredPrompt) return;
 deferredPrompt.prompt();
 const choice = await deferredPrompt.userChoice;
 if (choice.outcome === "accepted") {
 prompt.classList.add("hidden");
 }
 deferredPrompt = null;
 });

 dismissBtn?.addEventListener("click", function () {
 localStorage.setItem("pwa-dismissed", "1");
 prompt.classList.add("hidden");
 });
 })();
</script>