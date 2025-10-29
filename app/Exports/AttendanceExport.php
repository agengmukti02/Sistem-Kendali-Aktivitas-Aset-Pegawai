<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceExport
{
    protected $year;
    protected $month;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function export()
    {
        $startDate = Carbon::create($this->year, $this->month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth()->endOfDay();
        $daysInMonth = $startDate->daysInMonth;

        // Get employees with attendances
        $employees = Employee::with(['attendances' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate])
                  ->orderBy('date');
        }])
        ->orderBy('nip')
        ->get();

        // Prepare dates
        $dates = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dates[] = Carbon::create($this->year, $this->month, $day);
        }

        // Create CSV data structure
        $data = [];
        
        // Header row 1 - Date numbers
        $header1 = ['No', 'NIP', 'Nama'];
        foreach ($dates as $date) {
            $header1[] = $date->format('d');
        }
        $data[] = $header1;

        // Header row 2 - Day names
        $header2 = ['', '', ''];
        foreach ($dates as $date) {
            $header2[] = $date->format('D');
        }
        $data[] = $header2;

        // Data rows
        foreach ($employees as $index => $employee) {
            $row = [
                $index + 1,
                $employee->nip,
                $employee->name
            ];

            foreach ($dates as $date) {
                $attendance = $employee->attendances->first(function($att) use ($date) {
                    return $att->date->isSameDay($date);
                });

                if (!$attendance) {
                    $row[] = $date->isWeekend() ? '0' : 'TK';
                } else {
                    $row[] = $this->formatAttendanceForExport($attendance);
                }
            }

            $data[] = $row;
        }

        return $data;
    }

    private function formatAttendanceForExport(Attendance $attendance): string
    {
        if ($attendance->status === 'libur') {
            return '0';
        }

        if ($attendance->status === 'alpha') {
            return 'TK';
        }

        if ($attendance->status === 'hadir') {
            $text = '';
            
            if ($attendance->check_in) {
                $text .= $attendance->check_in->format('H:i');
            }

            if ($attendance->check_out) {
                $text .= ' - ' . $attendance->check_out->format('H:i');
            } elseif ($attendance->check_in) {
                $text .= ' - TPP';
            }

            return $text ?: 'H';
        }

        // For other statuses
        $codes = [
            'sakit' => 'S',
            'izin' => 'I',
            'cuti' => 'C',
            'dinas_dalam' => 'DD',
            'dinas_luar' => 'DL',
        ];

        return $codes[$attendance->status] ?? 'TK';
    }
}
