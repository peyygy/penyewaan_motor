<x-layouts.admin-dashboard>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Detail Motor</h2>
                <p class="text-gray-600">Informasi lengkap motor {{ $motor->merk }} - {{ $motor->no_plat }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.motors') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Kembali
                </a>
                @if($motor->status->value === 'pending')
                    <form method="POST" action="{{ route('admin.motors.verify', $motor) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700"
                            onclick="return confirm('Verifikasi motor ini?')">
                            Verifikasi Motor
                        </button>
                    </form>
                @elseif($motor->status->value === 'verified')
                    <form method="POST" action="{{ route('admin.motors.activate', $motor) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
                            onclick="return confirm('Aktifkan motor ini?')">
                            Aktifkan Motor
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Motor Information -->
        <div class="lg:col-span-2">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Motor</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Merk Motor</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->merk }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Plat</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->no_plat }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipe Motor</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->tipe->label() }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                            $motor->status->value === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                            ($motor->status->value === 'verified' ? 'bg-blue-100 text-blue-800' : 
                            ($motor->status->value === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) 
                        }}">
                            {{ $motor->status->label() }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Daftar</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Terakhir Update</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
                
                @if($motor->deskripsi)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->deskripsi }}</p>
                    </div>
                @endif
            </div>

            <!-- Owner Information -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pemilik</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->owner->nama }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->owner->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->owner->phone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->owner->alamat ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bergabung Sejak</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->owner->created_at->format('d F Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Motor</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motor->owner->motors()->count() }} motor</p>
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            @if($motor->tarif)
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Tarif</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga per Hari</label>
                            <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($motor->tarif->harga_per_hari, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga per Minggu</label>
                            <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($motor->tarif->harga_per_minggu, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga per Bulan</label>
                            <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($motor->tarif->harga_per_bulan, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deposito</label>
                            <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($motor->tarif->deposito, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Booking History -->
            @if($motor->bookings()->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Booking</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penyewa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($motor->bookings()->latest()->take(5)->get() as $booking)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->penyewa->nama }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $booking->tanggal_mulai->format('d M Y') }} - {{ $booking->tanggal_selesai->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($booking->harga, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                                                $booking->status->value === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                ($booking->status->value === 'confirmed' ? 'bg-blue-100 text-blue-800' : 
                                                ($booking->status->value === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) 
                                            }}">
                                                {{ $booking->status->label() }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Motor Photo -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Foto Motor</h3>
                @if($motor->foto_motor)
                    <img src="{{ Storage::url($motor->foto_motor) }}" alt="Motor {{ $motor->merk }}" 
                        class="w-full h-48 object-cover rounded-lg">
                @else
                    <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-4xl text-gray-400">🏍️</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Foto motor belum diupload</p>
                @endif
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Booking</span>
                        <span class="text-sm font-medium">{{ $motor->bookings()->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Booking Aktif</span>
                        <span class="text-sm font-medium">{{ $motor->bookings()->where('status', 'active')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Pendapatan</span>
                        <span class="text-sm font-medium">Rp {{ number_format($motor->bookings()->where('status', 'completed')->sum('harga'), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Status History -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-blue-500 mr-3"></div>
                        <div class="text-sm">
                            <p class="text-gray-900">Terdaftar</p>
                            <p class="text-gray-500">{{ $motor->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @if($motor->verified_at)
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full bg-green-500 mr-3"></div>
                            <div class="text-sm">
                                <p class="text-gray-900">Diverifikasi</p>
                                <p class="text-gray-500">{{ $motor->verified_at?->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-dashboard>