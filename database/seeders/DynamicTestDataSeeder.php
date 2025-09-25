<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Enums\UserRole;
use App\Enums\MotorStatus;
use App\Enums\BookingStatus;

class DynamicTestDataSeeder extends Seeder
{
    /**
     * Realistic Indonesian names for testing
     */
    private array $firstNames = [
        'Ahmad', 'Budi', 'Siti', 'Dewi', 'Andi', 'Rini', 'Joko', 'Maya', 'Dedi', 'Lia',
        'Agus', 'Nina', 'Heri', 'Sri', 'Tono', 'Yuli', 'Wawan', 'Fitri', 'Bambang', 'Indri',
        'Sutrisno', 'Kartini', 'Wahyu', 'Sari', 'Eko', 'Ratna', 'Teguh', 'Endang', 'Yanto', 'Titin'
    ];

    private array $lastNames = [
        'Pratama', 'Sari', 'Wijaya', 'Putri', 'Santoso', 'Lestari', 'Kurniawan', 'Anggraeni',
        'Setiawan', 'Maharani', 'Gunawan', 'Safitri', 'Budiman', 'Handayani', 'Nugraha', 'Pertiwi',
        'Permana', 'Rahayu', 'Susanto', 'Wulandari', 'Hidayat', 'Kusuma', 'Rahman', 'Melati'
    ];

    private array $motorBrands = [
        'Honda' => ['Beat', 'Vario', 'Scoopy', 'PCX', 'CBR', 'Sonic'],
        'Yamaha' => ['Mio', 'Aerox', 'NMAX', 'Jupiter', 'Vixion', 'R15'],
        'Suzuki' => ['Address', 'Nex', 'Satria', 'GSX', 'Smash', 'Spin'],
        'Kawasaki' => ['Ninja', 'KLX', 'Versys', 'W175', 'Athlete', 'Edge']
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ===================================
        // 1. DELETE ALL OLD DATA
        // ===================================
        $this->command->info('🗑️  Deleting old data...');
        
        // Disable foreign key checks for SQLite
        DB::statement('PRAGMA foreign_keys=OFF');
        
        // Delete in correct order (child tables first)
        DB::table('transaksis')->delete();
        DB::table('penyewaans')->delete(); 
        DB::table('tarif_rentals')->delete();
        DB::table('bagi_hasils')->delete();
        DB::table('motors')->delete();
        DB::table('users')->delete();
        
        // Reset auto-increment counters
        DB::statement('DELETE FROM sqlite_sequence WHERE name IN ("users", "motors", "penyewaans", "transaksis", "tarif_rentals", "bagi_hasils")');
        
        // Re-enable foreign key checks
        DB::statement('PRAGMA foreign_keys=ON');
        
        $this->command->info('✅ Old data deleted successfully');

        // ===================================
        // 2. INSERT DYNAMIC USER DATA
        // ===================================
        $this->command->info('👥 Creating dynamic user data...');
        
        $users = [];
        $now = Carbon::now();
        
        // 1 Admin User
        $users[] = [
            'nama' => 'System Administrator',
            'email' => 'admin@luxurymoto.com',
            'no_tlpn' => '08123456789',
            'password' => Hash::make('admin123'),
            'role' => UserRole::ADMIN->value,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now
        ];

        // 3 Pemilik (Owner) Users with dynamic names
        for ($i = 1; $i <= 3; $i++) {
            $firstName = $this->firstNames[array_rand($this->firstNames)];
            $lastName = $this->lastNames[array_rand($this->lastNames)];
            $fullName = $firstName . ' ' . $lastName;
            
            $users[] = [
                'nama' => $fullName,
                'email' => strtolower(str_replace(' ', '.', $firstName . '.' . $lastName)) . '.pemilik@example.com',
                'no_tlpn' => '0812' . str_pad(rand(1000000, 9999999), 7, '0'),
                'password' => Hash::make('pemilik123'),
                'role' => UserRole::PEMILIK->value,
                'email_verified_at' => $now,
                'created_at' => $now->copy()->subDays(rand(1, 30)),
                'updated_at' => $now
            ];
        }

        // 5 Penyewa (Renter) Users with dynamic names  
        for ($i = 1; $i <= 5; $i++) {
            $firstName = $this->firstNames[array_rand($this->firstNames)];
            $lastName = $this->lastNames[array_rand($this->lastNames)];
            $fullName = $firstName . ' ' . $lastName;
            
            $users[] = [
                'nama' => $fullName,
                'email' => strtolower(str_replace(' ', '.', $firstName . '.' . $lastName)) . '.penyewa@example.com',
                'no_tlpn' => '0813' . str_pad(rand(1000000, 9999999), 7, '0'),
                'password' => Hash::make('penyewa123'),
                'role' => UserRole::PENYEWA->value,
                'email_verified_at' => $now,
                'created_at' => $now->copy()->subDays(rand(1, 60)),
                'updated_at' => $now
            ];
        }

        DB::table('users')->insert($users);
        $this->command->info('✅ Created ' . count($users) . ' users (1 admin, 3 pemilik, 5 penyewa)');

        // ===================================
        // 3. INSERT 50 DYNAMIC MOTOR RECORDS
        // ===================================
        $this->command->info('🏍️  Creating 50 dynamic motor records...');
        
        // Get pemilik user IDs for foreign key relationships
        $pemilikIds = DB::table('users')->where('role', UserRole::PEMILIK->value)->pluck('id')->toArray();
        
        $motors = [];
        $tarifRentals = [];
        $usedPlates = [];
        
        for ($i = 1; $i <= 50; $i++) {
            // Generate random motor details
            $brand = array_rand($this->motorBrands);
            $model = $this->motorBrands[$brand][array_rand($this->motorBrands[$brand])];
            $fullName = $brand . ' ' . $model;
            
            // Generate unique plate number
            do {
                $plateNumber = $this->generatePlateNumber();
            } while (in_array($plateNumber, $usedPlates));
            $usedPlates[] = $plateNumber;
            
            // Random CC type and status
            $ccTypes = ['100', '125', '150'];
            $cc = $ccTypes[array_rand($ccTypes)];
            
            $statuses = [
                MotorStatus::AVAILABLE->value,
                MotorStatus::RENTED->value,
                MotorStatus::MAINTENANCE->value,
                MotorStatus::VERIFIED->value
            ];
            $status = $statuses[array_rand($statuses)];
            
            // Random owner
            $ownerId = $pemilikIds[array_rand($pemilikIds)];
            
            $createdAt = $now->copy()->subDays(rand(1, 90));
            
            $motors[] = [
                'id' => $i,
                'pemilik_id' => $ownerId,
                'merk' => $fullName,
                'tipe_cc' => $cc,
                'no_plat' => $plateNumber,
                'status' => $status,
                'photo' => null,
                'dokumen_kepemilikan' => null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt
            ];
            
            // Generate realistic pricing based on CC and brand
            $basePrice = match($cc) {
                '100' => rand(40000, 70000),
                '125' => rand(60000, 90000),
                '150' => rand(80000, 150000),
            };
            
            // Premium brands get higher pricing
            if (in_array($brand, ['Honda', 'Yamaha'])) {
                $basePrice = intval($basePrice * 1.2);
            }
            
            $tarifRentals[] = [
                'motor_id' => $i,
                'tarif_harian' => $basePrice,
                'tarif_mingguan' => intval($basePrice * 6), // 6 days price for weekly
                'tarif_bulanan' => intval($basePrice * 25), // 25 days price for monthly
                'created_at' => $createdAt,
                'updated_at' => $createdAt
            ];
        }
        
        DB::table('motors')->insert($motors);
        DB::table('tarif_rentals')->insert($tarifRentals);
        
        $this->command->info('✅ Created 50 motors with pricing');

        // ===================================
        // 4. CREATE SAMPLE BOOKINGS (OPTIONAL)
        // ===================================
        $this->command->info('📅 Creating sample bookings and transactions...');
        
        // Get penyewa IDs for bookings
        $penyewaIds = DB::table('users')->where('role', UserRole::PENYEWA->value)->pluck('id')->toArray();
        $availableMotorIds = DB::table('motors')->whereIn('status', ['available', 'rented'])->pluck('id')->toArray();
        
        $bookings = [];
        $transactions = [];
        
        // Create 15 random bookings
        for ($i = 1; $i <= 15; $i++) {
            $motorId = $availableMotorIds[array_rand($availableMotorIds)];
            $penyewaId = $penyewaIds[array_rand($penyewaIds)];
            
            // Get motor pricing
            $tarif = DB::table('tarif_rentals')->where('motor_id', $motorId)->first();
            
            $startDate = $now->copy()->subDays(rand(1, 30));
            $duration = rand(1, 7); // 1-7 days
            $endDate = $startDate->copy()->addDays($duration);
            
            $tipesDurasi = ['harian', 'mingguan', 'bulanan'];
            $tipeDurasi = $tipesDurasi[array_rand($tipesDurasi)];
            
            $harga = match($tipeDurasi) {
                'harian' => $tarif->tarif_harian * $duration,
                'mingguan' => $tarif->tarif_mingguan,
                'bulanan' => $tarif->tarif_bulanan,
            };
            
            $statuses = [BookingStatus::PENDING->value, BookingStatus::CONFIRMED->value, BookingStatus::ACTIVE->value, BookingStatus::COMPLETED->value];
            $bookingStatus = $statuses[array_rand($statuses)];
            
            $bookings[] = [
                'id' => $i,
                'penyewa_id' => $penyewaId,
                'motor_id' => $motorId,
                'tanggal_mulai' => $startDate->format('Y-m-d'),
                'tanggal_selesai' => $endDate->format('Y-m-d'),
                'tipe_durasi' => $tipeDurasi,
                'harga' => $harga,
                'status' => $bookingStatus,
                'catatan' => 'Test booking #' . $i,
                'created_at' => $startDate->copy()->subDays(1),
                'updated_at' => $startDate
            ];
            
            // Create transaction for each booking
            $transactionStatuses = ['pending', 'success', 'failed'];
            $transactionStatus = $transactionStatuses[array_rand($transactionStatuses)];
            $paymentMethods = ['bank_transfer', 'credit_card', 'e_wallet', 'cash'];
            
            $transactions[] = [
                'penyewaan_id' => $i,
                'jumlah' => $harga,
                'metode_pembayaran' => $paymentMethods[array_rand($paymentMethods)],
                'status' => $transactionStatus,
                'external_id' => 'TXN' . date('Ymd') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'payment_url' => null,
                'snap_token' => null,
                'paid_at' => $transactionStatus === 'success' ? $startDate : null,
                'created_at' => $startDate->copy()->subDays(1),
                'updated_at' => $startDate
            ];
        }
        
        DB::table('penyewaans')->insert($bookings);
        DB::table('transaksis')->insert($transactions);
        
        $this->command->info('✅ Created 15 sample bookings and transactions');

        // ===================================
        // 5. FINAL SUCCESS MESSAGE
        // ===================================
        $this->command->info('');
        $this->command->info('🎉 DYNAMIC TEST DATA GENERATION COMPLETED!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('   👥 Users: 9 total (1 admin, 3 pemilik, 5 penyewa)');
        $this->command->info('   🏍️  Motors: 50 with realistic pricing');
        $this->command->info('   📅 Bookings: 15 with transactions');
        $this->command->info('');
        $this->command->info('🔑 Login Credentials:');
        $this->command->info('   Admin: admin@luxurymoto.com / admin123');
        $this->command->info('   Pemilik: [name].pemilik@example.com / pemilik123');
        $this->command->info('   Penyewa: [name].penyewa@example.com / penyewa123');
        $this->command->info('');
    }

    /**
     * Generate realistic Indonesian plate number
     */
    private function generatePlateNumber(): string
    {
        $areas = ['B', 'D', 'F', 'H', 'L', 'N', 'R', 'T', 'Z', 'AA', 'AB'];
        $area = $areas[array_rand($areas)];
        $number = rand(1000, 9999);
        $letters = chr(rand(65, 90)) . chr(rand(65, 90)); // Random letters A-Z
        
        return $area . ' ' . $number . ' ' . $letters;
    }
}