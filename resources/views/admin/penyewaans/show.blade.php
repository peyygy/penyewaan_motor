@use('Illuminate\Support\Facades\Storage')

<x-layouts.admin-dashboard>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Detail Penyewaan #{{ str_pad($penyewaan->id, 4, '0', STR_PAD_LEFT) }}</h2>
                <p class="text-gray-600">Informasi lengkap penyewaan motor</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.penyewaans.edit', $penyewaan) }}" 
                   class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    ✏️ Edit
                </a>
                <a href="{{ route('admin.penyewaans.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status & Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Status & Aksi</h3>
                    @php
                        $statusClass = match($penyewaan->status->value) {
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'confirmed' => 'bg-blue-100 text-blue-800',
                            'active' => 'bg-green-100 text-green-800',
                            'completed' => 'bg-gray-100 text-gray-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-800'
                        };
                    @endphp
                    <span class="px-3 py-1 text-sm leading-5 font-semibold rounded-full {{ $statusClass }}">
                        {{ $penyewaan->status->label() }}
                    </span>
                </div>

                <div class="flex flex-wrap gap-3">
                    @if($penyewaan->status->value === 'pending')
                        <form action="{{ route('admin.penyewaans.confirm', $penyewaan) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                    onclick="return confirm('Konfirmasi penyewaan ini?')">
                                ✅ Konfirmasi
                            </button>
                        </form>
                    @endif

                    @if($penyewaan->status->value === 'confirmed')
                        <form action="{{ route('admin.penyewaans.activate', $penyewaan) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    onclick="return confirm('Aktifkan penyewaan ini?')">
                                ▶️ Aktifkan
                            </button>
                        </form>
                    @endif

                    @if($penyewaan->status->value === 'active')
                        <form action="{{ route('admin.penyewaans.complete', $penyewaan) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2"
                                    onclick="return confirm('Selesaikan penyewaan ini? Motor akan dikembalikan ke status tersedia.')">
                                ✅ Selesaikan
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Rental Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penyewaan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">ID Penyewaan</dt>
                        <dd class="mt-1 text-sm text-gray-900">#{{ str_pad($penyewaan->id, 4, '0', STR_PAD_LEFT) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $penyewaan->created_at->format('d M Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tanggal Mulai</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $penyewaan->tanggal_mulai->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tanggal Selesai</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $penyewaan->tanggal_selesai->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Durasi</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $penyewaan->getFormattedDuration() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipe Durasi</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($penyewaan->tipe_durasi) }}</dd>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pembayaran</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Harga</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($penyewaan->harga, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Sudah Dibayar</dt>
                        <dd class="mt-1 text-lg font-semibold text-green-600">Rp {{ number_format($penyewaan->getTotalPaid(), 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Sisa Tagihan</dt>
                        <dd class="mt-1 text-lg font-semibold text-red-600">Rp {{ number_format($penyewaan->harga - $penyewaan->getTotalPaid(), 0, ',', '.') }}</dd>
                    </div>
                </div>

                @if($penyewaan->transaksis->count() > 0)
                    <div class="border-t pt-4">
                        <h4 class="text-md font-medium text-gray-900 mb-3">Riwayat Transaksi</h4>
                        <div class="space-y-3">
                            @foreach($penyewaan->transaksis as $transaksi)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-md">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $transaksi->tanggal->format('d M Y H:i') }} • {{ $transaksi->metode_pembayaran }}
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $transaksi->status === 'success' ? 'bg-green-100 text-green-800' : 
                                           ($transaksi->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($transaksi->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Profit Sharing -->
            @if($penyewaan->bagiHasil)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bagi Hasil</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bagi Hasil Pemilik (70%)</dt>
                            <dd class="mt-1 text-lg font-semibold text-green-600">
                                Rp {{ number_format($penyewaan->bagiHasil->bagi_hasil_pemilik, 0, ',', '.') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bagi Hasil Admin (30%)</dt>
                            <dd class="mt-1 text-lg font-semibold text-blue-600">
                                Rp {{ number_format($penyewaan->bagiHasil->bagi_hasil_admin, 0, ',', '.') }}
                            </dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Tanggal Settlement</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $penyewaan->bagiHasil->settled_at->format('d M Y H:i') }}</dd>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Motor Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Motor</h3>
                <div class="space-y-4">
                    @if($penyewaan->motor->photo)
                        <img class="w-full h-32 object-cover rounded-md" 
                             src="{{ Storage::url($penyewaan->motor->photo) }}" alt="Motor Photo">
                    @endif
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Merk & Model</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $penyewaan->motor->merk }} {{ $penyewaan->motor->model }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nomor Plat</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $penyewaan->motor->no_plat }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipe CC</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $penyewaan->motor->tipe_cc?->label() ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Pemilik</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $penyewaan->motor->owner->nama }}</dd>
                    </div>
                </div>
            </div>

            <!-- Renter Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Penyewa</h3>
                <div class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nama</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $penyewaan->penyewa->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $penyewaan->penyewa->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">No. Telepon</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $penyewaan->penyewa->no_tlpn }}</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-dashboard>