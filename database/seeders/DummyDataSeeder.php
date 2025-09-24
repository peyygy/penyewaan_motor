<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    /**
     * ===============================
     * 🔐 SEEDED CREDENTIALS:
     * ===============================
     * 
     * 👤 ADMIN:
     *    📧 admin@sewa.com | 🔑 123456
     * 
     * 👤 PEMILIK:
     *    📧 pemilik1@sewa.com | 🔑 123456
     *    📧 pemilik2@sewa.com | 🔑 123456
     * 
     * 👤 PENYEWA:
     *    📧 penyewa1@sewa.com | 🔑 123456
     *    📧 penyewa2@sewa.com | 🔑 123456
     *    📧 penyewa3@sewa.com | 🔑 123456
     *    📧 penyewa4@sewa.com | 🔑 123456
     *    📧 penyewa5@sewa.com | 🔑 123456
     * 
     * ===============================
     */

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate all tables (safe for development)
        $this->truncateTables();
        
        // Set the base date for September 2024
        $baseDate = Carbon::create(2024, 9, 1);
        
        // Seed users
        $userIds = $this->seedUsers();
        
        // Seed motors
        $motorIds = $this->seedMotors($userIds);
        
        // Seed tarif rentals
        $this->seedTarifRentals($motorIds);
        
        // Seed penyewaans (September 2024 only)
        $penyewaanIds = $this->seedPenyewaans($userIds, $motorIds, $baseDate);
        
        // Seed transaksis
        $this->seedTransaksis($penyewaanIds, $baseDate);
        
        // Seed bagi hasils
        $this->seedBagiHasils($penyewaanIds, $baseDate);
        
        $this->command->info('DummyDataSeeder completed!');
    }

    /**
     * Truncate all related tables
     */
    private function truncateTables(): void
    {
        // For SQLite, we need to disable foreign key constraints differently
        DB::statement('PRAGMA foreign_keys = OFF');
        
        DB::table('bagi_hasils')->delete();
        DB::table('transaksis')->delete();
        DB::table('penyewaans')->delete();
        DB::table('tarif_rentals')->delete();
        DB::table('motors')->delete();
        DB::table('users')->delete();
        
        DB::statement('PRAGMA foreign_keys = ON');
        
        $this->command->info('🗑️  All tables truncated');
    }

    /**
     * Seed users (1 Admin, 2 Pemilik, 5 Penyewa)
     */
    private function seedUsers(): array
    {
        $now = Carbon::now();
        $userIds = [];

        // Admin
        $adminId = DB::table('users')->insertGetId([
            'nama' => 'Administrator',
            'email' => 'admin@sewa.com',
            'no_tlpn' => '081234567890',
            'email_verified_at' => $now,
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $userIds['admin'] = $adminId;

        // Pemilik accounts
        $userIds['pemilik'] = [];
        for ($i = 1; $i <= 2; $i++) {
            $pemilikId = DB::table('users')->insertGetId([
                'nama' => "Pemilik Motor {$i}",
                'email' => "pemilik{$i}@sewa.com",
                'no_tlpn' => "08123456789{$i}",
                'email_verified_at' => $now,
                'password' => Hash::make('123456'),
                'role' => 'pemilik',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $userIds['pemilik'][] = $pemilikId;
        }

        // Penyewa accounts
        $userIds['penyewa'] = [];
        for ($i = 1; $i <= 5; $i++) {
            $penyewaId = DB::table('users')->insertGetId([
                'nama' => "Penyewa {$i}",
                'email' => "penyewa{$i}@sewa.com",
                'no_tlpn' => "08567890123{$i}",
                'email_verified_at' => $now,
                'password' => Hash::make('123456'),
                'role' => 'penyewa',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $userIds['penyewa'][] = $penyewaId;
        }

        $this->command->info('👥 Users seeded: 1 Admin, 2 Pemilik, 5 Penyewa');
        return $userIds;
    }

    /**
     * Seed motors (2 motors per pemilik = 4 total)
     */
    private function seedMotors(array $userIds): array
    {
        $now = Carbon::now();
        $motorIds = [];
        
        $motorData = [
            // Pemilik 1 motors
            [
                'pemilik_id' => $userIds['pemilik'][0],
                'merk' => 'Honda Vario',
                'tipe_cc' => '125',
                'no_plat' => 'B 1234 ABC',
                'status' => 'available',
            ],
            [
                'pemilik_id' => $userIds['pemilik'][0],
                'merk' => 'Yamaha NMAX',
                'tipe_cc' => '150',
                'no_plat' => 'B 5678 DEF',
                'status' => 'available',
            ],
            // Pemilik 2 motors
            [
                'pemilik_id' => $userIds['pemilik'][1],
                'merk' => 'Honda Beat',
                'tipe_cc' => '100',
                'no_plat' => 'B 9101 GHI',
                'status' => 'available',
            ],
            [
                'pemilik_id' => $userIds['pemilik'][1],
                'merk' => 'Yamaha Mio',
                'tipe_cc' => '125',
                'no_plat' => 'B 1121 JKL',
                'status' => 'available',
            ],
        ];

        foreach ($motorData as $motor) {
            $motorId = DB::table('motors')->insertGetId([
                'pemilik_id' => $motor['pemilik_id'],
                'merk' => $motor['merk'],
                'tipe_cc' => $motor['tipe_cc'],
                'no_plat' => $motor['no_plat'],
                'status' => $motor['status'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $motorIds[] = $motorId;
        }

        $this->command->info('🏍️  Motors seeded: 4 motors (2 per pemilik)');
        return $motorIds;
    }

    /**
     * Seed tarif rentals for each motor
     */
    private function seedTarifRentals(array $motorIds): void
    {
        $now = Carbon::now();
        
        $tarifData = [
            // Honda Vario 125cc
            [
                'motor_id' => $motorIds[0],
                'tarif_harian' => 75000,
                'tarif_mingguan' => 450000,  // 6 days
                'tarif_bulanan' => 1800000,  // 24 days
            ],
            // Yamaha NMAX 150cc
            [
                'motor_id' => $motorIds[1],
                'tarif_harian' => 85000,
                'tarif_mingguan' => 510000,  // 6 days
                'tarif_bulanan' => 2040000,  // 24 days
            ],
            // Honda Beat 100cc
            [
                'motor_id' => $motorIds[2],
                'tarif_harian' => 60000,
                'tarif_mingguan' => 360000,  // 6 days
                'tarif_bulanan' => 1440000,  // 24 days
            ],
            // Yamaha Mio 125cc
            [
                'motor_id' => $motorIds[3],
                'tarif_harian' => 70000,
                'tarif_mingguan' => 420000,  // 6 days
                'tarif_bulanan' => 1680000,  // 24 days
            ],
        ];

        foreach ($tarifData as $tarif) {
            DB::table('tarif_rentals')->insert([
                'motor_id' => $tarif['motor_id'],
                'tarif_harian' => $tarif['tarif_harian'],
                'tarif_mingguan' => $tarif['tarif_mingguan'],
                'tarif_bulanan' => $tarif['tarif_bulanan'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info('💰 Tarif rentals seeded for all motors');
    }

    /**
     * Seed penyewaans (September 2024 only, at least 6 rentals)
     */
    private function seedPenyewaans(array $userIds, array $motorIds, Carbon $baseDate): array
    {
        $penyewaanIds = [];
        
        $penyewaanData = [
            // Rental 1: Penyewa 1 rents Honda Vario (daily)
            [
                'penyewa_id' => $userIds['penyewa'][0],
                'motor_id' => $motorIds[0],
                'tanggal_mulai' => $baseDate->copy()->addDays(1)->format('Y-m-d'), // Sept 2
                'tanggal_selesai' => $baseDate->copy()->addDays(3)->format('Y-m-d'), // Sept 4
                'tipe_durasi' => 'harian',
                'harga' => 225000, // 3 days * 75000
                'status' => 'completed',
            ],
            // Rental 2: Penyewa 2 rents Yamaha NMAX (weekly)
            [
                'penyewa_id' => $userIds['penyewa'][1],
                'motor_id' => $motorIds[1],
                'tanggal_mulai' => $baseDate->copy()->addDays(5)->format('Y-m-d'), // Sept 6
                'tanggal_selesai' => $baseDate->copy()->addDays(12)->format('Y-m-d'), // Sept 13
                'tipe_durasi' => 'mingguan',
                'harga' => 510000, // 1 week
                'status' => 'completed',
            ],
            // Rental 3: Penyewa 3 rents Honda Beat (daily)
            [
                'penyewa_id' => $userIds['penyewa'][2],
                'motor_id' => $motorIds[2],
                'tanggal_mulai' => $baseDate->copy()->addDays(8)->format('Y-m-d'), // Sept 9
                'tanggal_selesai' => $baseDate->copy()->addDays(10)->format('Y-m-d'), // Sept 11
                'tipe_durasi' => 'harian',
                'harga' => 180000, // 3 days * 60000
                'status' => 'completed',
            ],
            // Rental 4: Penyewa 4 rents Yamaha Mio (weekly)
            [
                'penyewa_id' => $userIds['penyewa'][3],
                'motor_id' => $motorIds[3],
                'tanggal_mulai' => $baseDate->copy()->addDays(14)->format('Y-m-d'), // Sept 15
                'tanggal_selesai' => $baseDate->copy()->addDays(21)->format('Y-m-d'), // Sept 22
                'tipe_durasi' => 'mingguan',
                'harga' => 420000, // 1 week
                'status' => 'completed',
            ],
            // Rental 5: Penyewa 5 rents Honda Vario (daily)
            [
                'penyewa_id' => $userIds['penyewa'][4],
                'motor_id' => $motorIds[0],
                'tanggal_mulai' => $baseDate->copy()->addDays(16)->format('Y-m-d'), // Sept 17
                'tanggal_selesai' => $baseDate->copy()->addDays(18)->format('Y-m-d'), // Sept 19
                'tipe_durasi' => 'harian',
                'harga' => 225000, // 3 days * 75000
                'status' => 'completed',
            ],
            // Rental 6: Penyewa 1 rents Yamaha NMAX (daily)
            [
                'penyewa_id' => $userIds['penyewa'][0],
                'motor_id' => $motorIds[1],
                'tanggal_mulai' => $baseDate->copy()->addDays(23)->format('Y-m-d'), // Sept 24
                'tanggal_selesai' => $baseDate->copy()->addDays(25)->format('Y-m-d'), // Sept 26
                'tipe_durasi' => 'harian',
                'harga' => 255000, // 3 days * 85000
                'status' => 'completed',
            ],
            // Rental 7: Penyewa 3 rents Honda Beat (weekly)
            [
                'penyewa_id' => $userIds['penyewa'][2],
                'motor_id' => $motorIds[2],
                'tanggal_mulai' => $baseDate->copy()->addDays(26)->format('Y-m-d'), // Sept 27
                'tanggal_selesai' => $baseDate->copy()->addDays(29)->format('Y-m-d'), // Sept 30
                'tipe_durasi' => 'harian',
                'harga' => 240000, // 4 days * 60000
                'status' => 'completed',
            ],
        ];

        foreach ($penyewaanData as $penyewaan) {
            $penyewaanId = DB::table('penyewaans')->insertGetId([
                'penyewa_id' => $penyewaan['penyewa_id'],
                'motor_id' => $penyewaan['motor_id'],
                'tanggal_mulai' => $penyewaan['tanggal_mulai'],
                'tanggal_selesai' => $penyewaan['tanggal_selesai'],
                'tipe_durasi' => $penyewaan['tipe_durasi'],
                'harga' => $penyewaan['harga'],
                'status' => $penyewaan['status'],
                'created_at' => Carbon::parse($penyewaan['tanggal_mulai'])->subDays(1),
                'updated_at' => Carbon::parse($penyewaan['tanggal_selesai'])->addDays(1),
            ]);
            $penyewaanIds[] = $penyewaanId;
        }

        $this->command->info('📅 Penyewaans seeded: 7 rentals in September 2024');
        return $penyewaanIds;
    }

    /**
     * Seed transaksis for each penyewaan
     */
    private function seedTransaksis(array $penyewaanIds, Carbon $baseDate): void
    {
        $penyewaans = DB::table('penyewaans')->whereIn('id', $penyewaanIds)->get();
        
        foreach ($penyewaans as $index => $penyewaan) {
            $paymentDate = Carbon::parse($penyewaan->tanggal_mulai)->subHours(2); // Pay 2 hours before start
            
            DB::table('transaksis')->insert([
                'penyewaan_id' => $penyewaan->id,
                'jumlah' => $penyewaan->harga,
                'metode_pembayaran' => ['bank_transfer', 'e_wallet', 'credit_card'][rand(0, 2)],
                'status' => 'success', // All successful as per requirement
                'external_id' => 'TRX-' . str_pad($penyewaan->id, 8, '0', STR_PAD_LEFT),
                'paid_at' => $paymentDate,
                'created_at' => $paymentDate->copy()->subMinutes(30),
                'updated_at' => $paymentDate,
            ]);
        }

        $this->command->info('💳 Transaksis seeded: All with status "success"');
    }

    /**
     * Seed bagi hasils (70% pemilik, 30% admin)
     */
    private function seedBagiHasils(array $penyewaanIds, Carbon $baseDate): void
    {
        $penyewaans = DB::table('penyewaans')->whereIn('id', $penyewaanIds)->get();
        
        foreach ($penyewaans as $penyewaan) {
            $totalAmount = $penyewaan->harga;
            $bagiHasilPemilik = (int) ($totalAmount * 0.70); // 70% for owner
            $bagiHasilAdmin = $totalAmount - $bagiHasilPemilik; // 30% for admin
            
            $settledDate = Carbon::parse($penyewaan->tanggal_selesai)->addDays(rand(2, 5)); // 2-5 days after completion
            
            DB::table('bagi_hasils')->insert([
                'penyewaan_id' => $penyewaan->id,
                'bagi_hasil_pemilik' => $bagiHasilPemilik,
                'bagi_hasil_admin' => $bagiHasilAdmin,
                'tanggal' => $penyewaan->tanggal_selesai,
                'settled_at' => $settledDate,
                'created_at' => Carbon::parse($penyewaan->tanggal_selesai)->addDays(1),
                'updated_at' => $settledDate,
            ]);
        }

        $this->command->info('💰 Bagi hasils seeded: 70% pemilik, 30% admin split');
    }
}