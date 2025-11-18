<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'Cash Record' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 font-sans antialiased">
    <div class="min-h-screen pb-20 sm:pb-6">
        <!-- Top Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 pt-safe">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-white text-lg sm:text-xl font-semibold">Your Money</h1>
                        <p class="text-blue-100 text-2xl sm:text-3xl font-bold mt-1">
                            ₹{{ number_format(\App\Models\Transaction::whereIn('direction', ['get', 'both'])->sum('amount') - \App\Models\Transaction::whereIn('direction', ['give', 'both'])->sum('amount'), 0) }}
                        </p>
                    </div>
                    <button class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4 sm:-mt-6">
            {{ $slot }}
        </main>

        <!-- Mobile Bottom Navigation -->
        <div class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-lg border-t border-gray-200/50 shadow-lg sm:hidden z-50 pb-safe rounded-t-3xl">
            <div class="grid grid-cols-2 px-6 py-2">
                <a href="/" wire:navigate class="flex flex-col items-center justify-center py-3 text-gray-600 hover:text-blue-600 btn-press">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center mb-1 {{ request()->is('/') ? 'bg-blue-500 text-white' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium">Home</span>
                </a>
                <a href="/people" wire:navigate class="flex flex-col items-center justify-center py-3 text-gray-600 hover:text-blue-600 btn-press">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center mb-1 {{ request()->is('people*') ? 'bg-blue-500 text-white' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium">History</span>
                </a>
            </div>
        </div>

        <!-- Desktop Navigation (Hidden on mobile) -->
        <div class="hidden sm:block fixed top-6 right-6 z-50">
            <div class="flex items-center space-x-3 bg-white/95 backdrop-blur-lg rounded-2xl px-4 py-2 shadow-lg">
                <a href="/" wire:navigate class="px-4 py-2 rounded-xl text-sm font-medium btn-press {{ request()->is('/') ? 'bg-blue-500 text-white shadow-md' : 'text-gray-700 hover:bg-gray-100' }}">
                    Home
                </a>
                <a href="/people" wire:navigate class="px-4 py-2 rounded-xl text-sm font-medium btn-press {{ request()->is('people*') ? 'bg-blue-500 text-white shadow-md' : 'text-gray-700 hover:bg-gray-100' }}">
                    History
                </a>
            </div>
        </div>
    </div>
    @livewireScripts
</body>
</html>
