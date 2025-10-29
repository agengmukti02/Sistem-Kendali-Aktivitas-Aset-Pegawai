<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 test users (test1 - test10)
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => 'Test User ' . $i,
                'email' => 'test' . $i . '@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info('✅ Created 10 test users (test1@example.com - test10@example.com)');
        $this->command->info('📧 Email: test1@example.com, test2@example.com, ..., test10@example.com');
        $this->command->info('🔑 Password: password');
    }
}
