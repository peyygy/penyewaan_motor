<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Enums\MotorStatus;
use App\Enums\UserRole;
use App\Models\BagiHasil;
use App\Models\Motor;
use App\Models\Penyewaan;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get available motors and renters
        $motors = Motor::where('status', MotorStatus::AVAILABLE)->get();
        $renters = User::where('role', UserRole::PENYEWA)->get();

        if ($motors->isEmpty() || $renters->isEmpty()) {
            $this->command->error('No available motors or renters found! Please run MotorSeeder and UserSeeder first.');
            return;
        }

        // Test data for September 2025
        $bookings = [
            // Completed booking
            [
                'penyewa_id' => $renters[0]->id,
                'motor_id' => $motors[0]->id,
                'tanggal_mulai' => '2025-09-01',
                'tanggal_selesai' => '2025-09-02',
                'tipe_durasi' => 'harian',
                'harga' => 75000,
                'status' => BookingStatus::COMPLETED,
                'created_at' => Carbon::create(2025, 9, 1, 10, 0, 0),
            ],
            // Active booking
            [
                'penyewa_id' => $renters[1]->id,
                'motor_id' => $motors[1]->id,
                'tanggal_mulai' => '2025-09-15',
                'tanggal_selesai' => '2025-09-22',
                'tipe_durasi' => 'mingguan',
                'harga' => 550000,
                'status' => BookingStatus::ACTIVE,
                'created_at' => Carbon::create(2025, 9, 14, 14, 30, 0),
            ],
            // Confirmed booking
            [
                'penyewa_id' => $renters[2]->id,
                'motor_id' => $motors[2]->id,
                'tanggal_mulai' => '2025-09-25',
                'tanggal_selesai' => '2025-10-25',
                'tipe_durasi' => 'bulanan',
                'harga' => 1900000,
                'status' => BookingStatus::CONFIRMED,
                'created_at' => Carbon::create(2025, 9, 20, 9, 15, 0),
            ],
            // Pending booking
            [
                'penyewa_id' => $renters[3]->id,
                'motor_id' => $motors[3]->id,
                'tanggal_mulai' => '2025-09-24',
                'tanggal_selesai' => '2025-09-25',
                'tipe_durasi' => 'harian',
                'harga' => 90000,
                'status' => BookingStatus::PENDING,
                'created_at' => Carbon::create(2025, 9, 23, 16, 45, 0),
            ],
            // Another completed booking
            [
                'penyewa_id' => $renters[0]->id,
                'motor_id' => $motors[4]->id,
                'tanggal_mulai' => '2025-09-05',
                'tanggal_selesai' => '2025-09-12',
                'tipe_durasi' => 'mingguan',
                'harga' => 650000,
                'status' => BookingStatus::COMPLETED,
                'created_at' => Carbon::create(2025, 9, 4, 11, 20, 0),
            ],
            // Monthly completed booking
            [
                'penyewa_id' => $renters[1]->id,
                'motor_id' => $motors[5]->id,
                'tanggal_mulai' => '2025-08-01',
                'tanggal_selesai' => '2025-09-01',
                'tipe_durasi' => 'bulanan',
                'harga' => 1800000,
                'status' => BookingStatus::COMPLETED,
                'created_at' => Carbon::create(2025, 7, 30, 13, 10, 0),
            ],
        ];

        foreach ($bookings as $bookingData) {
            $penyewaan = Penyewaan::create($bookingData);

            // Create transactions for non-pending bookings
            if ($penyewaan->status !== BookingStatus::PENDING) {
                Transaksi::create([
                    'penyewaan_id' => $penyewaan->id,
                    'jumlah' => $penyewaan->harga,
                    'metode_pembayaran' => fake()->randomElement(['midtrans_snap', 'transfer_bank']),
                    'status' => 'success',
                    'external_id' => 'TXN-' . time() . '-' . $penyewaan->id,
                    'paid_at' => $penyewaan->created_at->addHours(2),
                ]);
            }

            // Create revenue sharing for completed bookings
            if ($penyewaan->status === BookingStatus::COMPLETED) {
                $shares = BagiHasil::calculateShare($penyewaan->harga);
                
                BagiHasil::create([
                    'penyewaan_id' => $penyewaan->id,
                    'bagi_hasil_pemilik' => $shares['owner'],
                    'bagi_hasil_admin' => $shares['admin'],
                    'tanggal' => $penyewaan->tanggal_selesai,
                    'settled_at' => $penyewaan->tanggal_selesai->addDays(1),
                ]);
            }
        }

        $this->command->info('Sample bookings created successfully!');
        $this->command->info('6 bookings created with different statuses for September 2025 testing.');
    }
}