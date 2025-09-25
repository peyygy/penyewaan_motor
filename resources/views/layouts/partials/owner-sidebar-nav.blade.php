<!-- Owner Sidebar Navigation -->
<a href="{{ route('owner.dashboard') }}" 
   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('owner.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <span class="mr-3 text-lg">📊</span>
    Dashboard
</a>

<a href="{{ route('owner.motors.index') }}" 
   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('owner.motors.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <span class="mr-3 text-lg">🏍️</span>
    Motor Saya
</a>

<a href="{{ route('owner.bookings.index') }}" 
   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('owner.bookings.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <span class="mr-3 text-lg">📅</span>
    Booking Motor
</a>

<a href="{{ route('owner.revenue.index') }}" 
   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('owner.revenue.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <span class="mr-3 text-lg">💰</span>
    Pendapatan
</a>

{{-- Reports menu (akan diimplementasi nanti) --}}
{{--
<a href="{{ route('owner.reports.index') }}" 
   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('owner.reports.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <span class="mr-3 text-lg">📈</span>
    Laporan
</a>
--}}

{{-- Profile menu (akan diimplementasi nanti) --}}
{{--
<a href="{{ route('owner.profile.index') }}" 
   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('owner.profile.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <span class="mr-3 text-lg">👤</span>
    Profil
</a>
--}}