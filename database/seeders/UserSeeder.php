<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password')
        ]);
        $admin->assignRole('admin');

        // Create Manager
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@test.com',
            'password' => Hash::make('password')
        ]);
        $manager->assignRole('manager');

        // Create Employee
        $employee = User::create([
            'name' => 'Employee',
            'email' => 'employee@test.com',
            'password' => Hash::make('password')
        ]);
        $employee->assignRole('employee');
    }
}
