<?php

namespace Database\Seeders;

use App\Enums\MotorStatus;
use App\Enums\MotorType;
use App\Models\Motor;
use App\Models\TarifRental;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;

class MotorSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get all owners
        $owners = User::where('role', UserRole::PEMILIK)->get();

        if ($owners->isEmpty()) {
            $this->command->error('No owners found! Please run UserSeeder first.');
            return;
        }

        // Sample motors data
        $motors = [
            [
                'pemilik_id' => $owners[0]->id,
                'merk' => 'Honda Beat',
                'tipe_cc' => MotorType::CC_125,
                'no_plat' => 'B 1234 ABC',
                'status' => MotorStatus::AVAILABLE,
                'tarif' => [
                    'tarif_harian' => 75000,
                    'tarif_mingguan' => 500000,
                    'tarif_bulanan' => 2000000,
                ]
            ],
            [
                'pemilik_id' => $owners[0]->id,
                'merk' => 'Yamaha Mio',
                'tipe_cc' => MotorType::CC_125,
                'no_plat' => 'B 5678 DEF',
                'status' => MotorStatus::AVAILABLE,
                'tarif' => [
                    'tarif_harian' => 80000,
                    'tarif_mingguan' => 550000,
                    'tarif_bulanan' => 2200000,
                ]
            ],
            [
                'pemilik_id' => $owners[1]->id,
                'merk' => 'Suzuki Nex',
                'tipe_cc' => MotorType::CC_125,
                'no_plat' => 'B 9012 GHI',
                'status' => MotorStatus::AVAILABLE,
                'tarif' => [
                    'tarif_harian' => 70000,
                    'tarif_mingguan' => 480000,
                    'tarif_bulanan' => 1900000,
                ]
            ],
            [
                'pemilik_id' => $owners[1]->id,
                'merk' => 'Honda Vario',
                'tipe_cc' => MotorType::CC_150,
                'no_plat' => 'B 3456 JKL',
                'status' => MotorStatus::AVAILABLE,
                'tarif' => [
                    'tarif_harian' => 90000,
                    'tarif_mingguan' => 620000,
                    'tarif_bulanan' => 2500000,
                ]
            ],
            [
                'pemilik_id' => $owners[2]->id,
                'merk' => 'Yamaha Aerox',
                'tipe_cc' => MotorType::CC_150,
                'no_plat' => 'B 7890 MNO',
                'status' => MotorStatus::AVAILABLE,
                'tarif' => [
                    'tarif_harian' => 95000,
                    'tarif_mingguan' => 650000,
                    'tarif_bulanan' => 2600000,
                ]
            ],
            [
                'pemilik_id' => $owners[2]->id,
                'merk' => 'Honda Scoopy',
                'tipe_cc' => MotorType::CC_100,
                'no_plat' => 'B 2468 PQR',
                'status' => MotorStatus::AVAILABLE,
                'tarif' => [
                    'tarif_harian' => 65000,
                    'tarif_mingguan' => 450000,
                    'tarif_bulanan' => 1800000,
                ]
            ],
            // Some pending motors for testing
            [
                'pemilik_id' => $owners[0]->id,
                'merk' => 'Yamaha Xeon',
                'tipe_cc' => MotorType::CC_125,
                'no_plat' => 'B 1357 STU',
                'status' => MotorStatus::PENDING,
            ],
            [
                'pemilik_id' => $owners[1]->id,
                'merk' => 'Honda PCX',
                'tipe_cc' => MotorType::CC_150,
                'no_plat' => 'B 2468 VWX',
                'status' => MotorStatus::VERIFIED,
            ],
        ];

        foreach ($motors as $motorData) {
            $tarif = $motorData['tarif'] ?? null;
            unset($motorData['tarif']);

            $motor = Motor::create($motorData);

            // Create tarif if motor is available and has rates
            if ($tarif && $motor->status === MotorStatus::AVAILABLE) {
                TarifRental::create([
                    'motor_id' => $motor->id,
                    ...$tarif
                ]);
            }
        }

        $this->command->info('Sample motors created successfully!');
        $this->command->info('6 Available motors, 1 Pending, and 1 Verified motor created.');
    }
}