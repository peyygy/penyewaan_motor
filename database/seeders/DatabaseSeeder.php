<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // DummyDataSeeder::class, // Old static seeder
            DynamicTestDataSeeder::class, // New dynamic seeder
        ]);

        $this->command->info('');
        $this->command->info(' Database seeded success!');
        $this->command->info('');
        $this->command->info('👤 Login Credentials:');
        $this->command->info('📧 Admin: admin@sewa.com | 🔑 Password: 123456');
        $this->command->info('📧 Pemilik 1: pemilik1@sewa.com | 🔑 Password: 123456');
        $this->command->info('📧 Pemilik 2: pemilik2@sewa.com | 🔑 Password: 123456');
        $this->command->info('📧 Penyewa 1: penyewa1@sewa.com | 🔑 Password: 123456');
        $this->command->info('📧 Penyewa 2: penyewa2@sewa.com | 🔑 Password: 123456');
        $this->command->info('📧 Penyewa 3: penyewa3@sewa.com | 🔑 Password: 123456');
        $this->command->info('📧 Penyewa 4: penyewa4@sewa.com | 🔑 Password: 123456');
        $this->command->info('📧 Penyewa 5: penyewa5@sewa.com | 🔑 Password: 123456');
        $this->command->info('');
        $this->command->info('🏍️  Sample data includes:');
        $this->command->info('   • 1 Admin, 2 Pemilik, 5 Penyewa');
        $this->command->info('   • 4 Motors (2 per pemilik)');
        $this->command->info('   • 7 Completed rentals in September 2024');
        $this->command->info('   • All payments successful with bagi hasil settled');
        $this->command->info('');
    }
}
