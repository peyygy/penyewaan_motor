<x-layouts.owner-dashboard>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Daftar Booking Pemilik</h2>
        <p class="text-gray-600 mt-2">Kelola booking untuk motor-motor Anda</p>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyewa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Sewa</th>
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
                                    <a href="{{ route('owner.bookings.show', $booking) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
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
                <p class="text-gray-600">Booking untuk motor Anda akan ditampilkan di sini</p>
            </div>
        @endif
    </div>
</x-layouts.owner-dashboard>