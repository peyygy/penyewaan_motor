<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RentMotorcycle') }} - Admin Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-800 text-white">
            <div class="p-6">
                <div class="flex items-center">
                    <x-shared.logo class="w-8 h-8 text-white mr-3" />
                    <div>
                        <h1 class="text-lg font-bold">RentMotorcycle</h1>
                        <p class="text-xs text-blue-200">Admin Dashboard</p>
                    </div>
                </div>
            </div>
            
            <nav class="mt-6">
                <div class="px-6 py-3">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center text-white hover:bg-blue-700 px-4 py-2 rounded">
                        <span>📊</span>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </div>
                <div class="px-6 py-3">
                    <a href="{{ route('admin.motors-verification.index') }}" class="flex items-center {{ request()->routeIs('admin.motors-verification*') ? 'text-white bg-blue-700' : 'text-blue-200 hover:bg-blue-700 hover:text-white' }} px-4 py-2 rounded">
                        <span>🏍️</span>
                        <span class="ml-3">Verifikasi Motor</span>
                    </a>
                </div>
                <div class="px-6 py-3">
                    <a href="{{ route('admin.motors.index') }}" class="flex items-center {{ request()->routeIs('admin.motors.*') && !request()->routeIs('admin.motors-verification*') ? 'text-white bg-blue-700' : 'text-blue-200 hover:bg-blue-700 hover:text-white' }} px-4 py-2 rounded">
                        <span>🏍️</span>
                        <span class="ml-3">Kelola Motor</span>
                    </a>
                </div>
                <div class="px-6 py-3">
                    <a href="{{ route('admin.users.index') }}" class="flex items-center {{ request()->routeIs('admin.users.*') ? 'text-white bg-blue-700' : 'text-blue-200 hover:bg-blue-700 hover:text-white' }} px-4 py-2 rounded">
                        <span>👥</span>
                        <span class="ml-3">Kelola User</span>
                    </a>
                </div>
                <div class="px-6 py-3">
                    <a href="{{ route('admin.penyewaans.index') }}" class="flex items-center {{ request()->routeIs('admin.penyewaans.*') ? 'text-white bg-blue-700' : 'text-blue-200 hover:bg-blue-700 hover:text-white' }} px-4 py-2 rounded">
                        <span>📋</span>
                        <span class="ml-3">Kelola Penyewaan</span>
                    </a>
                </div>
                <div class="px-6 py-3">
                    <a href="{{ route('admin.bookings.index') }}" class="flex items-center {{ request()->routeIs('admin.bookings.*') ? 'text-white bg-blue-700' : 'text-blue-200 hover:bg-blue-700 hover:text-white' }} px-4 py-2 rounded">
                        <span>🎯</span>
                        <span class="ml-3">Kelola Booking</span>
                    </a>
                </div>
                <div class="px-6 py-3">
                    <a href="{{ route('admin.tarif.index') }}" class="flex items-center {{ request()->routeIs('admin.tarif.*') ? 'text-white bg-blue-700' : 'text-blue-200 hover:bg-blue-700 hover:text-white' }} px-4 py-2 rounded">
                        <span>💰</span>
                        <span class="ml-3">Tarif Rental</span>
                    </a>
                </div>
                <div class="px-6 py-3">
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center {{ request()->routeIs('admin.reports.*') ? 'text-white bg-blue-700' : 'text-blue-200 hover:bg-blue-700 hover:text-white' }} px-4 py-2 rounded">
                        <span>📈</span>
                        <span class="ml-3">Laporan</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <button id="userMenuButton" class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 focus:outline-none">
                                    <span class="text-sm">{{ auth()->user()->nama }}</span>
                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                        {{ substr(auth()->user()->nama, 0, 1) }}
                                    </div>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        // Toggle user menu dropdown
        document.getElementById('userMenuButton').addEventListener('click', function() {
            document.getElementById('userMenu').classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('userMenu');
            const userMenuButton = document.getElementById('userMenuButton');
            
            if (!userMenuButton.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>