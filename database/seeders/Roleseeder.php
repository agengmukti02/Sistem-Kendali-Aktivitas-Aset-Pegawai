<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Role
        $roles = ['admin', 'operator', 'atasan', 'pegawai'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Buat Admin Default
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password123'),
            ]
        );
        $admin->assignRole('admin');

        // Buat Operator Default
        $operator = User::firstOrCreate(
            ['email' => 'operator@example.com'],
            [
                'name' => 'Operator User',
                'password' => bcrypt('password123'),
            ]
        );
        $operator->assignRole('operator');

        // Buat Atasan Default
        $atasan = User::firstOrCreate(
            ['email' => 'atasan@example.com'],
            [
                'name' => 'Atasan User',
                'password' => bcrypt('password123'),
            ]
        );
        $atasan->assignRole('atasan');

        // Buat Pegawai Default
        $pegawai = User::firstOrCreate(
            ['email' => 'pegawai@example.com'],
            [
                'name' => 'Pegawai User',
                'password' => bcrypt('password123'),
            ]   
        );
        $pegawai->assignRole('pegawai');

    }


}
