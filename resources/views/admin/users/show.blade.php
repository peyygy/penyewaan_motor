<x-layouts.admin-dashboard>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Detail User</h2>
                <p class="text-gray-600">Informasi lengkap pengguna</p>
            </div>
            <a href="{{ route('admin.users.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16">
                    <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-xl font-medium text-gray-700">{{ substr($user->nama, 0, 2) }}</span>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-semibold text-gray-900">{{ $user->nama }}</h3>
                    <div class="flex items-center space-x-2 mt-1">
                        @if($user->role->value === 'pemilik')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                🏍️ Pemilik Motor
                            </span>
                        @elseif($user->role->value === 'penyewa')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                👤 Penyewa
                            </span>
                        @endif
                        <span class="text-sm text-gray-500">ID: {{ $user->id }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="px-6 py-4">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Informasi Kontak</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->no_tlpn ?? 'Tidak ada' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Daftar</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d F Y, H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Terakhir Update</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($user->isOwner())
        <!-- Owner's Motors -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-lg font-medium text-gray-900">Motor yang Dimiliki</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarif Harian</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Booking</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($user->motors as $motor)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $motor->merk }} {{ $motor->model }}</div>
                                    <div class="text-sm text-gray-500">{{ $motor->plat_nomor }} • {{ $motor->tahun }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($motor->status->value === 'available')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Tersedia
                                        </span>
                                    @elseif($motor->status->value === 'rented')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Disewa
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ $motor->status->value }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($motor->tarif)
                                        Rp {{ number_format($motor->tarif->harga_per_hari, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $motor->penyewaans->count() }} booking
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Belum ada motor terdaftar
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if($user->isRenter())
        <!-- Renter's Bookings -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-lg font-medium text-gray-900">Riwayat Booking</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($user->penyewaans as $booking)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $booking->motor->merk }} {{ $booking->motor->model }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->motor->plat_nomor }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->motor->owner->nama }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->tanggal_mulai->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-500">sampai {{ $booking->tanggal_selesai->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($booking->status->value === 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    @elseif($booking->status->value === 'completed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Selesai
                                        </span>
                                    @elseif($booking->status->value === 'pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ $booking->status->value }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($booking->harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Belum ada booking
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Danger Zone -->
    @if(!$user->isAdmin())
        <div class="mt-8 bg-red-50 border border-red-200 rounded-lg p-6">
            <h4 class="text-lg font-medium text-red-900 mb-2">Danger Zone</h4>
            <p class="text-sm text-red-700 mb-4">
                Tindakan ini tidak dapat dibatalkan. User akan dihapus secara permanen dari sistem.
            </p>
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->nama }}? Tindakan ini tidak dapat dibatalkan!')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    🗑️ Hapus User
                </button>
            </form>
        </div>
    @endif
</x-layouts.admin-dashboard>