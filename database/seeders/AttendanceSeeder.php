<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $employees = Employee::all();
        
        if ($employees->isEmpty()) {
            $this->command->warn('No employees found. Please run EmployeeSeeder first.');
            return;
        }

        // Generate attendance for current month (October 2025)
        $year = 2025;
        $month = 10;
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        
        $this->command->info("Generating attendance data for {$employees->count()} employees for October 2025...");

        foreach ($employees as $employee) {
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($year, $month, $day);
                
                // Skip if attendance already exists
                if (Attendance::where('employee_id', $employee->id)->whereDate('date', $date)->exists()) {
                    continue;
                }

                $attendance = $this->generateAttendanceForDay($employee->id, $date);
                
                if ($attendance) {
                    Attendance::create($attendance);
                }
            }
        }

        $this->command->info('Attendance data generated successfully!');
    }

    private function generateAttendanceForDay(int $employeeId, Carbon $date): ?array
    {
        // Weekend is libur by default
        if ($date->isWeekend()) {
            return [
                'employee_id' => $employeeId,
                'date' => $date,
                'status' => 'libur',
                'notes' => 'Weekend'
            ];
        }

        // Random attendance pattern (realistic distribution)
        $random = rand(1, 100);
        
        // 85% hadir, 5% sakit, 3% izin, 2% cuti, 2% dinas, 3% alpha
        if ($random <= 85) {
            // Hadir - generate realistic times
            $checkIn = $this->generateCheckInTime();
            $checkOut = $this->generateCheckOutTime($checkIn);
            
            return [
                'employee_id' => $employeeId,
                'date' => $date,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'status' => 'hadir',
            ];
        } elseif ($random <= 90) {
            return [
                'employee_id' => $employeeId,
                'date' => $date,
                'status' => 'sakit',
                'notes' => 'Sakit demam'
            ];
        } elseif ($random <= 93) {
            return [
                'employee_id' => $employeeId,
                'date' => $date,
                'status' => 'izin',
                'notes' => 'Izin keperluan keluarga'
            ];
        } elseif ($random <= 95) {
            return [
                'employee_id' => $employeeId,
                'date' => $date,
                'status' => 'cuti',
                'notes' => 'Cuti tahunan'
            ];
        } elseif ($random <= 97) {
            $status = rand(0, 1) ? 'dinas_dalam' : 'dinas_luar';
            $checkIn = $this->generateCheckInTime();
            
            return [
                'employee_id' => $employeeId,
                'date' => $date,
                'check_in' => $checkIn,
                'status' => $status,
                'notes' => $status === 'dinas_dalam' ? 'Rapat di kantor pusat' : 'Kunjungan ke klien'
            ];
        } else {
            // Alpha
            return [
                'employee_id' => $employeeId,
                'date' => $date,
                'status' => 'alpha',
                'notes' => 'Tanpa keterangan'
            ];
        }
    }

    private function generateCheckInTime(): string
    {
        // Generate check-in between 06:30 - 08:30
        $hour = rand(6, 8);
        $minute = rand(0, 59);
        
        // More realistic distribution (peak at 07:00-08:00)
        if (rand(1, 100) <= 70) {
            $hour = 7;
            $minute = rand(0, 59);
        }
        
        return sprintf('%02d:%02d:00', $hour, $minute);
    }

    private function generateCheckOutTime(string $checkIn): ?string
    {
        // 90% chance of having check out
        if (rand(1, 100) <= 90) {
            $checkInTime = Carbon::createFromTimeString($checkIn);
            
            // Add 8-9 hours for normal working hours
            $workHours = rand(8, 9);
            $extraMinutes = rand(0, 59);
            
            $checkOut = $checkInTime->copy()->addHours($workHours)->addMinutes($extraMinutes);
            
            return $checkOut->format('H:i:s');
        }
        
        return null; // Sometimes people forget to check out
    }
}
