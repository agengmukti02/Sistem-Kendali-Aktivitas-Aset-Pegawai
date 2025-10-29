<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class TestEmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatanList = ['Staff', 'Supervisor', 'Manager', 'Team Lead', 'Senior Staff'];
        $golonganList = ['I/a', 'I/b', 'II/a', 'II/b', 'III/a', 'III/b', 'IV/a'];
        $statusList = ['aktif', 'nonaktif'];

        // Create 10 test employees (employee1 - employee10)
        for ($i = 11; $i <= 20; $i++) {
            Employee::create([
                'nip' => 'EMP' . str_pad($i, 4, '0', STR_PAD_LEFT), // EMP0011, EMP0012, etc
                'name' => 'Employee Test ' . ($i - 10),
                'jabatan' => $jabatanList[($i - 11) % count($jabatanList)],
                'golongan' => $golonganList[($i - 11) % count($golonganList)],
                'status' => $statusList[($i - 11) % count($statusList)],
            ]);
        }

        $this->command->info('✅ Created 10 test employees (EMP0011 - EMP0020)');
        $this->command->info('👤 Name: Employee Test 1, Employee Test 2, ..., Employee Test 10');
        $this->command->info('📋 NIP: EMP0011, EMP0012, ..., EMP0020');
    }
}
