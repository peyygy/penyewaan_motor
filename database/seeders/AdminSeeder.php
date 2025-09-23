<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'nama' => 'Administrator',
            'email' => 'admin@rentmotorcycle.com',
            'no_tlpn' => '08123456789',
            'password' => Hash::make('admin123'),
            'role' => UserRole::ADMIN,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Default admin user created successfully!');
        $this->command->info('Email: admin@rentmotorcycle.com');
        $this->command->info('Password: admin123');
    }
}