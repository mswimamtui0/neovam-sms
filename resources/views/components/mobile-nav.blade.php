<!-- Mobile Navigation -->
<div id="mobile-header" class="md:hidden bg-blue-900 text-white p-4 flex justify-between items-center sticky top-0 z-40">
    <div class="flex items-center gap-2">
        <img src="/logo.png" alt="NEOVAM" class="h-8 w-auto" onerror="this.style.display='none'">
        <span class="font-bold">NEOVAM SMS</span>
    </div>
    <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')" class="text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
</div>