<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create sample owners (pemilik)
        $owners = [
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi@owner.com',
                'no_tlpn' => '08123456790',
                'password' => Hash::make('123456'),
                'role' => UserRole::PEMILIK,
            ],
            [
                'nama' => 'Sari Dewi',
                'email' => 'sari@owner.com',
                'no_tlpn' => '08123456791',
                'password' => Hash::make('123456'),
                'role' => UserRole::PEMILIK,
            ],
            [
                'nama' => 'Ahmad Rahman',
                'email' => 'ahmad@owner.com',
                'no_tlpn' => '08123456792',
                'password' => Hash::make('123456'),
                'role' => UserRole::PEMILIK,
            ]
        ];

        foreach ($owners as $owner) {
            User::create($owner);
        }

        // Create sample renters (penyewa)
        $renters = [
            [
                'nama' => 'John Doe',
                'email' => 'john@renter.com',
                'no_tlpn' => '08123456793',
                'password' => Hash::make('123456'),
                'role' => UserRole::PENYEWA,
            ],
            [
                'nama' => 'Jane Smith',
                'email' => 'jane@renter.com',
                'no_tlpn' => '08123456794',
                'password' => Hash::make('123456'),
                'role' => UserRole::PENYEWA,
            ],
            [
                'nama' => 'Mike Johnson',
                'email' => 'mike@renter.com',
                'no_tlpn' => '08123456795',
                'password' => Hash::make('123456'),
                'role' => UserRole::PENYEWA,
            ],
            [
                'nama' => 'Lisa Wilson',
                'email' => 'lisa@renter.com',
                'no_tlpn' => '08123456796',
                'password' => Hash::make('123456'),
                'role' => UserRole::PENYEWA,
            ]
        ];

        foreach ($renters as $renter) {
            User::create($renter);
        }

        $this->command->info('Sample users created successfully!');
        $this->command->info('3 Owners and 4 Renters created with password: 123456');
    }
}