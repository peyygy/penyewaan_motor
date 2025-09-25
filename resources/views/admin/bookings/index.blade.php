<x-layouts.admin-dashboard>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Daftar Semua Booking (Admin)</h2>
        <p class="text-gray-600 mt-2">Kelola semua booking dalam sistem</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-md">
                    <span class="text-lg">⏳</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $bookings->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-md">
                    <span class="text-lg">✅</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Confirmed</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $bookings->where('status', 'confirmed')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-md">
                    <span class="text-lg">🚀</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $bookings->where('status', 'active')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-gray-100 rounded-md">
                    <span class="text-lg">✔️</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $bookings->where('status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($bookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyewa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $booking->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $booking->motor->merk ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $booking->motor->no_plat ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $booking->motor->pemilik->nama ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $booking->motor->pemilik->email ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $booking->user->nama ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $booking->user->email ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($booking->status === 'pending')
                                            bg-yellow-100 text-yellow-800
                                        @elseif($booking->status === 'confirmed')
                                            bg-blue-100 text-blue-800
                                        @elseif($booking->status === 'active')
                                            bg-green-100 text-green-800
                                        @elseif($booking->status === 'completed')
                                            bg-gray-100 text-gray-800
                                        @elseif($booking->status === 'cancelled')
                                            bg-red-100 text-red-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>{{ $booking->tanggal_mulai ? \Carbon\Carbon::parse($booking->tanggal_mulai)->format('d/m/Y') : 'N/A' }}</div>
                                    <div class="text-gray-500">s/d {{ $booking->tanggal_selesai ? \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d/m/Y') : 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                    <a href="{{ route('admin.bookings.edit', $booking) }}" 
                                       class="text-green-600 hover:text-green-900">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-4xl mb-4">📋</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada booking</h3>
                <p class="text-gray-600">Booking dalam sistem akan ditampilkan di sini</p>
            </div>
        @endif
    </div>
</x-layouts.admin-dashboard>