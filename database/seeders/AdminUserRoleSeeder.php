<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default admin user if it doesn't exist
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@espee.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Update existing admin user to admin role
        if ($adminUser->role !== 'admin') {
            $adminUser->update(['role' => 'admin']);
        }

        $this->command->info('Admin user created/updated successfully!');
        $this->command->info('Email: admin@espee.com');
        $this->command->info('Password: password');
    }
}
