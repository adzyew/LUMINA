<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            FeatureSeeder::class,
            ProductSeeder::class,
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@lumina.local'],
            [
                'name' => 'Lumina Admin',
                'password' => Hash::make('Admin@12345'),
                'is_admin' => true,
                'email_verified_at' => now(),
                'is_verified' => true,
            ]
        );
        $admin->assignRole('admin');

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('Password@12345'),
                'email_verified_at' => now(),
                'is_verified' => true,
            ]
        );
    }
}
