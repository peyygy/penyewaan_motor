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
            AdminSeeder::class,
            UserSeeder::class,
            MotorSeeder::class,
            BookingSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('🎉 Database seeded successfully!');
        $this->command->info('');
        $this->command->info('👤 Login Credentials:');
        $this->command->info('📧 Admin: admin@rentmotorcycle.com | 🔑 Password: admin123');
        $this->command->info('📧 Owner: budi@owner.com | 🔑 Password: 123456');
        $this->command->info('📧 Renter: john@renter.com | 🔑 Password: 123456');
        $this->command->info('');
        $this->command->info('🏍️  Sample data includes:');
        $this->command->info('   • 1 Admin, 3 Owners, 4 Renters');
        $this->command->info('   • 8 Motors (6 available, 1 pending, 1 verified)');
        $this->command->info('   • 6 Sample bookings for September 2025');
        $this->command->info('   • Revenue sharing records');
        $this->command->info('');
    }
}
