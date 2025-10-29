<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Asset;
use App\Models\Activity;
use App\Models\Loan;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // First, create permissions and roles
        $this->call([
            PermissionSeeder::class,
        ]);

        // Create admin user
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        
        // Assign admin role
        $adminUser->assignRole('admin');

        // Create sample employees
        $employee1 = Employee::create([
            'name' => 'John Doe',
            'nip' => 'EMP001',
            'golongan' => 'III/a',
            'jabatan' => 'Manager',
            'status' => 'aktif',
        ]);

        $employee2 = Employee::create([
            'name' => 'Jane Smith', 
            'nip' => 'EMP002',
            'golongan' => 'II/d',
            'jabatan' => 'Staff',
            'status' => 'aktif',
        ]);

        // Create sample assets
        Asset::create([
            'nama' => 'Laptop Dell XPS 13',
            'kode' => 'ASSET001',
            'kategori' => 'komputer',
            'kondisi' => 'baik',
            'status' => 'tersedia',
        ]);

        Asset::create([
            'nama' => 'Printer Canon',
            'kode' => 'ASSET002', 
            'kategori' => 'printer',
            'kondisi' => 'baik',
            'status' => 'tersedia',
        ]);

        Asset::create([
            'nama' => 'Mobil Dinas Toyota',
            'kode' => 'ASSET003', 
            'kategori' => 'kendaraan',
            'kondisi' => 'baik',
            'status' => 'dipinjam',
        ]);

        // Create sample activities
        Activity::create([
            'employee_id' => $employee1->id,
            'jenis' => 'dinas',
            'tanggal_awal' => now()->subDays(7),
            'tanggal_akhir' => now()->subDays(5),
            'nomor_surat' => 'SP001/2025',
            'tanggal_surat' => now()->subDays(10),
            'keperluan' => 'Meeting dengan klien',
            'uraian' => 'Rapat koordinasi proyek baru dengan klien di Jakarta',
        ]);

        Activity::create([
            'employee_id' => $employee2->id,
            'jenis' => 'diklat',
            'tanggal_awal' => now()->addDays(3),
            'tanggal_akhir' => now()->addDays(5),
            'nomor_surat' => 'SP002/2025',
            'tanggal_surat' => now(),
            'keperluan' => 'Pelatihan keamanan siber',
            'uraian' => 'Mengikuti pelatihan keamanan siber tingkat lanjut',
        ]);
    }
}
