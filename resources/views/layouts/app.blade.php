<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'Cash Record' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
    </style>
</head>
<body class="bg-[#5CB8E4] font-sans antialiased">
    <div class="min-h-screen pb-24">
        <!-- Page Content -->
        <main class="max-w-md mx-auto px-4 sm:px-6 pt-6 sm:pt-8">
            {{ $slot }}
        </main>

        <!-- Bottom Navigation with FAB -->
        <div class="fixed bottom-0 left-0 right-0 z-50">
            <div class="max-w-md mx-auto px-4">
                <div class="bg-white rounded-3xl shadow-2xl p-3 mb-4 relative">
                    <!-- Centered FAB Button -->
                    <div class="absolute -top-8 left-1/2 -translate-x-1/2">
                        <button onclick="window.livewire.find('{{ request()->is('/') ? 'dashboard' : 'people-list' }}')?.openForm?.()" class="w-16 h-16 bg-[#4A9FD8] hover:bg-[#3A8FC8] rounded-full flex items-center justify-center shadow-2xl transition-all">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-5 gap-2">
                        <!-- Home -->
                        <a href="/" wire:navigate class="flex flex-col items-center justify-center py-3 rounded-2xl transition-colors {{ request()->is('/') ? 'text-[#4A9FD8]' : 'text-gray-400' }}">
                            <svg class="w-6 h-6" fill="{{ request()->is('/') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </a>

                        <!-- Charts -->
                        <button class="flex flex-col items-center justify-center py-3 rounded-2xl transition-colors text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </button>

                        <!-- Spacer for FAB -->
                        <div></div>

                        <!-- History -->
                        <a href="/people" wire:navigate class="flex flex-col items-center justify-center py-3 rounded-2xl transition-colors {{ request()->is('people*') ? 'text-[#4A9FD8]' : 'text-gray-400' }}">
                            <svg class="w-6 h-6" fill="{{ request()->is('people*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </a>

                        <!-- Settings -->
                        <button class="flex flex-col items-center justify-center py-3 rounded-2xl transition-colors text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewireScripts
</body>
</html>
