<x-layouts.admin-dashboard>
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Manajemen Motor</h2>
        <p class="text-gray-600">Kelola dan verifikasi motor yang terdaftar dalam sistem</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Motor</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Merk, plat, atau owner..."
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="rented" {{ request('status') === 'rented' ? 'selected' : '' }}>Disewa</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Motor</label>
                <select name="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Tipe</option>
                    <option value="matic" {{ request('type') === 'matic' ? 'selected' : '' }}>Matic</option>
                    <option value="manual" {{ request('type') === 'manual' ? 'selected' : '' }}>Manual</option>
                    <option value="sport" {{ request('type') === 'sport' ? 'selected' : '' }}>Sport</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 mr-2">
                    Cari
                </button>
                <a href="{{ route('admin.motors') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Motor Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Motor ({{ $motors->total() }})</h3>
                <div class="flex space-x-2">
                    @if(request()->filled(['search', 'status', 'type']))
                        <span class="text-sm text-gray-500">Filter aktif</span>
                    @endif
                </div>
            </div>
        </div>

        @if($motors->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarif</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($motors as $motor)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($motor->photo)
                                                <img class="h-10 w-10 rounded-lg object-cover" src="{{ Storage::url($motor->photo) }}" alt="Motor">
                                            @else
                                                <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <span class="text-gray-500 text-xs">🏍️</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $motor->merk }}</div>
                                            <div class="text-sm text-gray-500">{{ $motor->no_plat }} • {{ $motor->tipe->label() }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $motor->owner->nama }}</div>
                                    <div class="text-sm text-gray-500">{{ $motor->owner->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($motor->tarif)
                                        <div class="text-sm text-gray-900">Rp {{ number_format($motor->tarif->harga_per_hari, 0, ',', '.') }}/hari</div>
                                        <div class="text-sm text-gray-500">Rp {{ number_format($motor->tarif->harga_per_minggu, 0, ',', '.') }}/minggu</div>
                                    @else
                                        <span class="text-sm text-gray-500">Belum ada tarif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                                        $motor->status->value === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                        ($motor->status->value === 'verified' ? 'bg-blue-100 text-blue-800' : 
                                        ($motor->status->value === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) 
                                    }}">
                                        {{ $motor->status->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $motor->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.motors.show', $motor) }}" 
                                            class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                        
                                        @if($motor->status->value === 'pending')
                                            <form method="POST" action="{{ route('admin.motors.verify', $motor) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                    class="text-green-600 hover:text-green-900 ml-2"
                                                    onclick="return confirm('Verifikasi motor ini?')">
                                                    Verifikasi
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($motor->status->value === 'verified')
                                            <form method="POST" action="{{ route('admin.motors.activate', $motor) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                    class="text-blue-600 hover:text-blue-900 ml-2"
                                                    onclick="return confirm('Aktifkan motor ini?')">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $motors->links() }}
            </div>
        @else
            <div class="p-6 text-center">
                <div class="text-gray-500">
                    <span class="text-4xl mb-4 block">🏍️</span>
                    <p class="text-lg font-medium">Tidak ada motor ditemukan</p>
                    <p class="text-sm">Coba ubah filter pencarian atau tunggu owner mendaftarkan motor baru</p>
                </div>
            </div>
        @endif
    </div>
</x-layouts.admin-dashboard>