<x-layouts.admin-dashboard>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Manajemen User</h2>
                <p class="text-gray-600">Kelola semua pengguna sistem</p>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4">
                <!-- Search Input -->
                <div class="flex-1 min-w-64">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari berdasarkan nama, email, atau telepon..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <!-- Role Filter -->
                <div class="min-w-48">
                    <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Role</option>
                        <option value="pemilik" {{ request('role') == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                        <option value="penyewa" {{ request('role') == 'penyewa' ? 'selected' : '' }}>Penyewa</option>
                    </select>
                </div>
                
                <!-- Filter Button -->
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    🔍 Filter
                </button>
                
                <!-- Reset Button -->
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    🔄 Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terdaftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <!-- User Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">{{ substr($user->nama, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->nama }}</div>
                                        <div class="text-sm text-gray-500">ID: {{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                <div class="text-sm text-gray-500">{{ $user->no_tlpn ?? 'Tidak ada' }}</div>
                            </td>

                            <!-- Role -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role->value === 'pemilik')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        🏍️ Pemilik
                                    </span>
                                @elseif($user->role->value === 'penyewa')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        👤 Penyewa
                                    </span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role->value === 'pemilik')
                                    <div class="text-sm text-gray-900">{{ $user->motors->count() }} Motor</div>
                                @elseif($user->role->value === 'penyewa')
                                    <div class="text-sm text-gray-900">{{ $user->penyewaans->where('status', 'active')->count() }} Aktif</div>
                                @endif
                            </td>

                            <!-- Registration Date -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="relative">
                                    <!-- Three Dots Button -->
                                    <button onclick="toggleDropdown('user-{{ $user->id }}')" 
                                            class="inline-flex items-center p-2 text-gray-400 bg-white rounded-lg hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div id="dropdown-user-{{ $user->id }}" 
                                         class="hidden absolute right-0 z-10 w-44 bg-white rounded-lg shadow-lg border border-gray-200">
                                        
                                        <!-- View Action -->
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg">
                                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Lihat Detail
                                        </a>

                                        @if(!$user->isAdmin())
                                        <hr class="border-gray-100">
                                        
                                        <!-- Delete Action -->
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline-block w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Yakin ingin menghapus user {{ $user->nama }}?')"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50 rounded-b-lg">
                                                <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus User
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                <div class="flex flex-col items-center py-8">
                                    <span class="text-6xl mb-4">👥</span>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada user ditemukan</h3>
                                    <p class="text-gray-500">Coba ubah filter pencarian Anda</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- Summary Stats -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-md">
                    <span class="text-2xl">👥</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total User</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $users->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-md">
                    <span class="text-2xl">🏍️</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pemilik Motor</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $users->where('role.value', 'pemilik')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-md">
                    <span class="text-2xl">🚴</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Penyewa</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $users->where('role.value', 'penyewa')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for dropdown functionality -->
    <script>
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById('dropdown-' + dropdownId);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-user-"]');
            
            // Close all other dropdowns
            allDropdowns.forEach(function(otherDropdown) {
                if (otherDropdown.id !== 'dropdown-' + dropdownId) {
                    otherDropdown.classList.add('hidden');
                }
            });
            
            // Toggle the current dropdown
            dropdown.classList.toggle('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.relative')) {
                const allDropdowns = document.querySelectorAll('[id^="dropdown-user-"]');
                allDropdowns.forEach(function(dropdown) {
                    dropdown.classList.add('hidden');
                });
            }
        });
    </script>
</x-layouts.admin-dashboard>