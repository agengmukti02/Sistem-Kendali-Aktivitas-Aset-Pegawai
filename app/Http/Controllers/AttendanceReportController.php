<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceReportController extends Controller
{
    public function monthlyReport(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        
        // Validate year and month
        $year = (int) $year;
        $month = (int) $month;
        
        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }
        
        $startDate = Carbon::create($year, $month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth()->endOfDay();
        $daysInMonth = $startDate->daysInMonth;
        
        // Get all employees with their attendances for the month
        $employees = Employee::with(['attendances' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate])
                  ->orderBy('date');
        }])
        ->orderBy('nip')
        ->get();
        
        // Prepare dates array
        $dates = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);
            $dates[] = [
                'date' => $date,
                'day' => $day,
                'dayName' => $date->format('D'),
                'isWeekend' => $date->isWeekend(),
            ];
        }
        
        // Get statistics
        $stats = [
            'total_employees' => $employees->count(),
            'total_present' => Attendance::whereBetween('date', [$startDate, $endDate])
                ->where('status', 'hadir')
                ->whereNotNull('check_in')
                ->count(),
            'total_absent' => Attendance::whereBetween('date', [$startDate, $endDate])
                ->where('status', 'alpha')
                ->count(),
            'total_leave' => Attendance::whereBetween('date', [$startDate, $endDate])
                ->whereIn('status', ['sakit', 'izin', 'cuti'])
                ->count(),
            'working_days' => $startDate->diffInWeekdays($endDate) + 1,
        ];
        
        return view('attendance.monthly-report', [
            'employees' => $employees,
            'dates' => $dates,
            'year' => $year,
            'month' => $month,
            'monthName' => $startDate->format('F'),
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
    
    public function print(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        
        // Reuse the same logic but with print view
        $data = $this->getReportData($year, $month);
        
        return view('attendance.print', $data);
    }
    
    public function exportExcel(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        
        $exporter = new \App\Exports\AttendanceExport($year, $month);
        $data = $exporter->export();
        
        // Generate CSV content
        $filename = 'laporan_presensi_' . $year . '_' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.csv';
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    
    private function getReportData($year, $month)
    {
        $year = (int) $year;
        $month = (int) $month;
        
        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }
        
        $startDate = Carbon::create($year, $month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth()->endOfDay();
        $daysInMonth = $startDate->daysInMonth;
        
        $employees = Employee::with(['attendances' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate])
                  ->orderBy('date');
        }])
        ->orderBy('nip')
        ->get();
        
        $dates = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);
            $dates[] = [
                'date' => $date,
                'day' => $day,
                'dayName' => $date->format('D'),
                'isWeekend' => $date->isWeekend(),
            ];
        }
        
        return [
            'employees' => $employees,
            'dates' => $dates,
            'year' => $year,
            'month' => $month,
            'monthName' => $startDate->format('F'),
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }
}
