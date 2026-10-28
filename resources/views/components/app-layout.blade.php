{{-- app-layout component --}}
<div class="min-h-screen flex">
    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 w-64 bg-[#f3f4f6] border-r border-[#e5e7eb] flex flex-col z-40 transition-transform duration-200" id="sidebar">
        <div class="flex items-center justify-between h-16 px-4 border-b border-[#e5e7eb]">
            <div class="flex items-center gap-2">
                <div class="flex aspect-square size-8 items-center justify-center rounded-lg bg-[#6366f1] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 12h6"/><path d="M12 9v6"/></svg>
                </div>
                <span class="font-semibold text-lg">Perhutangan</span>
            </div>
            <button class="p-1 rounded hover:bg-[#e5e7eb]" onclick="toggleSidebar()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto py-4">
            <nav class="px-2 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium bg-[#6366f1]/10 text-[#6366f1]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 12h6"/><path d="M12 9v6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('tasks.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-[#4b5563] hover:bg-[#e5e7eb] hover:text-[#171717] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h7"/></svg>
                    Tasks
                </a>
                <a href="{{ route('apps.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-[#4b5563] hover:bg-[#e5e7eb] hover:text-[#171717] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="14" x="3" y="3" rx="2"/><path d="M3 21h18"/></svg>
                    Apps
                </a>
                <a href="{{ route('chats.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-[#4b5563] hover:bg-[#e5e7eb] hover:text-[#171717] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
                    Chats <span class="bg-[#6366f1]/10 text-[#6366f1] px-1.5 py-0.5 rounded-full text-xs">3</span>
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-[#4b5563] hover:bg-[#e5e7eb] hover:text-[#171717] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    Users
                </a>
            </nav>
        </div>
        <div class="border-t border-[#e5e7eb] p-2">
            <div class="flex items-center gap-3 px-2 py-2 rounded-lg">
                <div class="flex aspect-square size-9 items-center justify-center rounded-full bg-[#6366f1]/10 text-[#6366f1] font-semibold text-sm">SN</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">Satya N.</p>
                    <p class="text-xs text-[#4b5563] truncate">satnaingdev@gmail.com</p>
                </div>
                <button class="p-1 rounded hover:bg-[#e5e7eb] text-[#171717]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                </button>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col min-h-screen">
        {{-- Header --}}
        <header class="h-16 bg-white border-b border-[#e5e7eb] flex items-center px-4 gap-3">
            <button class="p-1 rounded hover:bg-[#f9fafb] border border-transparent hover:border-[#e5e7eb] transition-colors" onclick="toggleSidebar()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <nav class="hidden md:flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-[#4b5563] hover:text-[#171717] transition-colors">Overview</a>
                <a href="#" class="text-sm font-medium text-[#4b5563] hover:text-[#171717] transition-colors">Customers</a>
                <a href="#" class="text-sm font-medium text-[#4b5563] hover:text-[#171717] transition-colors">Products</a>
                <a href="{{ route('settings.index') }}" class="text-sm font-medium text-[#4b5563] hover:text-[#171717] transition-colors">Settings</a>
            </nav>
            <div class="relative w-32 hidden sm:block">
                <input type="text" placeholder="Search..." class="w-full pl-8 pr-3 py-1.5 text-sm border border-[#e5e7eb] rounded-lg bg-[#f9fafb] focus:outline-none focus:ring-2 focus:ring-[#6366f1]/20 focus:border-[#6366f1]">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-2 top-1/2 -translate-y-1/2 text-[#9ca3af]"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            </div>
            <div class="flex items-center gap-2">
                <button class="p-1 rounded hover:bg-[#f9fafb] border border-transparent hover:border-[#e5e7eb] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                </button>
                <button class="p-1 rounded hover:bg-[#f9fafb] border border-transparent hover:border-[#e5e7eb] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </button>
                <div class="w-px h-6 bg-[#e5e7eb] mx-2"></div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-[#6366f1] to-[#a855f7] flex items-center justify-center text-white text-sm font-semibold">SN</div>
                    <div class="hidden sm:flex flex-col">
                        <p class="text-sm font-medium">Satya N.</p>
                        <p class="text-xs text-[#4b5563]">Admin</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- Main --}}
        <main class="flex-1 p-4 md:p-6 lg:p-8">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="text-center text-sm text-[#4b5563] border-t border-[#e5e7eb] mt-auto px-4 py-4 flex items-center justify-center gap-2">
            <span>&copy; {{ date('Y') }} All rights reserved by <a href="https://github.com/satnaing" target="_blank" class="text-[#171717] font-medium underline-offset-4 hover:underline">Sat Naing</a></span>
            <span class="flex-1">|</span>
            <span>Distributed by <a href="https://themewagon.com/" target="_blank" class="text-[#171717] font-medium underline-offset-4 hover:underline">ThemeWagon</a></span>
        </footer>
    </div>
</div>

<script>
    function toggleSidebar(){
        const s=document.getElementById('sidebar');
        s.classList.toggle('translate-x-[-100%]');
    }
    if(window.innerWidth < 640){
        document.getElementById('sidebar')?.classList.add('translate-x-[-100%]');
    }
</script>