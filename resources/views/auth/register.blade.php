<x-layouts.auth>
    <div class="mb-4 text-center">
        <h2 class="text-xl font-semibold text-gray-900">Daftar Akun Baru</h2>
        <p class="text-sm text-gray-600 mt-1">Bergabung dengan RentMotorcycle</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-ui.input 
                id="nama" 
                name="nama" 
                type="text" 
                label="Nama Lengkap" 
                :value="old('nama')" 
                required 
                autofocus 
                placeholder="Masukkan nama lengkap"
            />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-ui.input 
                id="email" 
                name="email" 
                type="email" 
                label="Email" 
                :value="old('email')" 
                required 
                placeholder="Masukkan email Anda"
            />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-ui.input 
                id="no_tlpn" 
                name="no_tlpn" 
                type="text" 
                label="Nomor Telepon" 
                :value="old('no_tlpn')" 
                required 
                placeholder="08123456789"
            />
        </div>

        <!-- Role Selection -->
        <div class="mt-4">
            <label class="block font-medium text-sm text-gray-700 mb-2">Daftar Sebagai</label>
            <div class="space-y-2">
                <div class="flex items-center">
                    <input id="role_pemilik" name="role" type="radio" value="pemilik" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" {{ old('role') == 'pemilik' ? 'checked' : '' }}>
                    <label for="role_pemilik" class="ml-3 block text-sm font-medium text-gray-700">
                        Pemilik Kendaraan
                        <span class="block text-xs text-gray-500">Saya ingin menyewakan motor saya</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <input id="role_penyewa" name="role" type="radio" value="penyewa" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" {{ old('role') == 'penyewa' ? 'checked' : '' }}>
                    <label for="role_penyewa" class="ml-3 block text-sm font-medium text-gray-700">
                        Penyewa
                        <span class="block text-xs text-gray-500">Saya ingin menyewa motor</span>
                    </label>
                </div>
            </div>
            @error('role')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-ui.input 
                id="password" 
                name="password" 
                type="password" 
                label="Password" 
                required 
                placeholder="Minimal 6 karakter"
            />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-ui.input 
                id="password_confirmation" 
                name="password_confirmation" 
                type="password" 
                label="Konfirmasi Password" 
                required 
                placeholder="Ulangi password"
            />
        </div>

        <!-- Terms and Conditions -->
        <div class="block mt-4">
            <label for="terms" class="inline-flex items-center">
                <input id="terms" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="terms">
                <span class="ml-2 text-sm text-gray-600">
                    Saya menyetujui <a href="#" class="underline hover:text-gray-900">Syarat dan Ketentuan</a>
                </span>
            </label>
            @error('terms')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('login') }}">
                Sudah punya akun?
            </a>

            <x-ui.button type="submit" class="ml-3">
                Daftar
            </x-ui.button>
        </div>
    </form>
</x-layouts.auth>