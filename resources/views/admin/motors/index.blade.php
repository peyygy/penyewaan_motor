<x-layouts.admin-dashboard>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Manajemen Motor</h2>
                <p class="text-gray-600">Kelola semua motor yang terdaftar dalam sistem</p>
            </div>
            <a href="{{ route('admin.motors.create') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                ➕ Tambah Motor
            </a>
        </div>
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
                <a href="{{ route('admin.motors.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
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
                                            <div class="text-sm text-gray-500">{{ $motor->no_plat }} • {{ $motor->tipe?->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $motor->owner->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $motor->owner->phone ?? 'N/A' }}</div>
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
                                        $motor->status?->value === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                        ($motor->status?->value === 'verified' ? 'bg-blue-100 text-blue-800' : 
                                        ($motor->status?->value === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) 
                                    }}">
                                        {{ $motor->status?->name ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $motor->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="relative inline-block text-left">
                                        <button type="button" 
                                                class="inline-flex items-center justify-center w-8 h-8 bg-white rounded-full hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                onclick="toggleDropdown({{ $motor->id }})">
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>
                                        
                                        <div id="dropdown-{{ $motor->id }}" 
                                             class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                            <div class="py-1">
                                                <a href="{{ route('admin.motors.show', $motor) }}" 
                                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Lihat Detail
                                                </a>
                                                
                                                <a href="{{ route('admin.motors.edit', $motor) }}" 
                                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit Motor
                                                </a>

                                                @if($motor->status?->value === 'pending')
                                                    <div class="border-t border-gray-100"></div>
                                                    <button type="button"
                                                            onclick="verifyMotor({{ $motor->id }})"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-green-700 hover:bg-green-50">
                                                        <svg class="w-4 h-4 mr-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Verifikasi Motor
                                                    </button>
                                                    
                                                    <button type="button"
                                                            onclick="rejectMotor({{ $motor->id }})"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                        <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                        Tolak Motor
                                                    </button>
                                                @endif

                                                @if($motor->status?->value === 'verified')
                                                    <div class="border-t border-gray-100"></div>
                                                    <button type="button"
                                                            onclick="activateMotor({{ $motor->id }})"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-blue-700 hover:bg-blue-50">
                                                        <svg class="w-4 h-4 mr-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"/>
                                                        </svg>
                                                        Aktifkan Motor
                                                    </button>
                                                @endif

                                                <div class="border-t border-gray-100"></div>
                                                <button type="button"
                                                        onclick="deleteMotor({{ $motor->id }}, '{{ $motor->merk }} {{ $motor->model }}')"
                                                        class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                    <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Hapus Motor
                                                </button>
                                            </div>
                                        </div>
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

<script>
// Toggle dropdown menu
function toggleDropdown(motorId) {
    const dropdown = document.getElementById(`dropdown-${motorId}`);
    const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
    
    // Close all other dropdowns
    allDropdowns.forEach(dd => {
        if (dd.id !== `dropdown-${motorId}`) {
            dd.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick*="toggleDropdown"]')) {
        const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
        allDropdowns.forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    }
});

// Motor verification function
function verifyMotor(motorId) {
    if (confirm('Apakah Anda yakin ingin memverifikasi motor ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/motors/${motorId}/verify`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Motor activation function  
function activateMotor(motorId) {
    if (confirm('Apakah Anda yakin ingin mengaktifkan motor ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/motors/${motorId}/activate`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Motor rejection function
function rejectMotor(motorId) {
    if (confirm('Apakah Anda yakin ingin menolak motor ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/motors/${motorId}/reject`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Motor deletion function
function deleteMotor(motorId, motorName) {
    if (confirm(`Apakah Anda yakin ingin menghapus motor ${motorName}? Data ini tidak dapat dikembalikan.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/motors/${motorId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>