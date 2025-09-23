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
                    <a href="#" class="flex items-center text-blue-200 hover:bg-blue-700 hover:text-white px-4 py-2 rounded">
                        <span>🏍️</span>
                        <span class="ml-3">Verifikasi Motor</span>
                    </a>
                </div>
                <div class="px-6 py-3">
                    <a href="#" class="flex items-center text-blue-200 hover:bg-blue-700 hover:text-white px-4 py-2 rounded">
                        <span>👥</span>
                        <span class="ml-3">Kelola User</span>
                    </a>
                </div>
                <div class="px-6 py-3">
                    <a href="#" class="flex items-center text-blue-200 hover:bg-blue-700 hover:text-white px-4 py-2 rounded">
                        <span>💰</span>
                        <span class="ml-3">Tarif Rental</span>
                    </a>
                </div>
                <div class="px-6 py-3">
                    <a href="#" class="flex items-center text-blue-200 hover:bg-blue-700 hover:text-white px-4 py-2 rounded">
                        <span>📈</span>
                        <span class="ml-3">Laporan</span>
                    </a>
                </div>
            </nav>

            <div class="absolute bottom-4 left-4 right-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left text-blue-200 hover:bg-blue-700 hover:text-white px-4 py-2 rounded">
                        <span>🚪</span>
                        <span class="ml-3">Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">{{ auth()->user()->nama }}</span>
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                {{ substr(auth()->user()->nama, 0, 1) }}
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
</body>
</html>