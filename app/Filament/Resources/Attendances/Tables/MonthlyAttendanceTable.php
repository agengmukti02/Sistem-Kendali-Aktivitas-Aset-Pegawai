<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class MonthlyAttendanceTable
{
    public static function configure(Table $table, int $year = null, int $month = null): Table
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        // Get days in month
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        $monthName = Carbon::create($year, $month, 1)->format('M');
        
        // Build columns array
        $columns = [
            // Fixed columns - Sticky
            TextColumn::make('row_number')
                ->label('No')
                ->rowIndex()
                ->alignCenter()
                ->toggleable(false)
                ->extraHeaderAttributes([
                    'class' => 'sticky left-0 z-10 bg-white',
                    'style' => 'min-width: 50px;'
                ])
                ->extraAttributes([
                    'class' => 'sticky left-0 z-10 bg-white',
                ]),
                
            TextColumn::make('nip')
                ->label('NIP')
                ->searchable()
                ->sortable()
                ->toggleable(false)
                ->weight('semibold')
                ->copyable()
                ->extraHeaderAttributes([
                    'class' => 'sticky left-[50px] z-10 bg-white',
                    'style' => 'min-width: 120px;'
                ])
                ->extraAttributes([
                    'class' => 'sticky left-[50px] z-10 bg-white',
                ]),
                
            TextColumn::make('name')
                ->label('Nama')
                ->searchable()
                ->sortable()
                ->toggleable(false)
                ->weight('semibold')
                ->wrap()
                ->extraHeaderAttributes([
                    'class' => 'sticky left-[170px] z-10 bg-white',
                    'style' => 'min-width: 200px;'
                ])
                ->extraAttributes([
                    'class' => 'sticky left-[170px] z-10 bg-white',
                ]),
        ];

        // Add dynamic date columns
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);
            $dayName = $date->format('D'); // Mon, Tue, etc
            $isWeekend = $date->isWeekend();
            
            $columns[] = TextColumn::make("day_{$day}")
                ->label($monthName . '-' . sprintf('%02d', $day))
                ->html()
                ->alignCenter()
                ->getStateUsing(function (Employee $record) use ($date) {
                    return self::getAttendanceDisplay($record->id, $date);
                })
                ->tooltip(function (Employee $record) use ($date) {
                    return self::getAttendanceTooltip($record->id, $date);
                })
                ->extraHeaderAttributes([
                    'class' => $isWeekend ? 'bg-red-50 text-red-700' : 'bg-gray-50',
                    'style' => 'min-width: 80px; font-size: 11px;'
                ])
                ->extraAttributes([
                    'class' => $isWeekend ? 'bg-red-25' : '',
                    'style' => 'min-width: 80px; font-size: 11px; padding: 4px;'
                ]);
        }

        return $table
            ->query(Employee::query()->with(['attendances' => function ($query) use ($year, $month) {
                $query->forMonth($year, $month);
            }]))
            ->columns($columns)
            ->filters([
                SelectFilter::make('month')
                    ->label('Bulan')
                    ->options([
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                        4 => 'April', 5 => 'Mei', 6 => 'Juni',
                        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                        10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ])
                    ->default($month)
                    ->query(function (Builder $query, array $data) {
                        // This will be handled by the resource
                        return $query;
                    }),
                    
                SelectFilter::make('year')
                    ->label('Tahun')
                    ->options([
                        2023 => '2023',
                        2024 => '2024', 
                        2025 => '2025',
                        2026 => '2026',
                    ])
                    ->default($year)
                    ->query(function (Builder $query, array $data) {
                        // This will be handled by the resource
                        return $query;
                    }),
                    
                SelectFilter::make('jabatan')
                    ->label('Jabatan')
                    ->options(function () {
                        return Employee::whereNotNull('jabatan')
                            ->distinct()
                            ->pluck('jabatan', 'jabatan')
                            ->toArray();
                    })
                    ->placeholder('Semua Jabatan'),
            ])
            ->searchable()
            ->defaultSort('nip')
            ->paginated([10, 25, 50, 100])
            ->striped()
            ->emptyStateHeading('Tidak ada data pegawai')
            ->emptyStateDescription('Belum ada data pegawai yang terdaftar.')
            ->persistFiltersInSession()
            ->persistSortInSession();
    }

    private static function getAttendanceDisplay(int $employeeId, Carbon $date): string
    {
        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', $date)
            ->first();

        if (!$attendance) {
            // Check if it's weekend (default libur)
            if ($date->isWeekend()) {
                return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">0</span>';
            }
            // No data for weekday (Alpha)
            return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">TK</span>';
        }

        $colors = [
            'hadir' => 'bg-green-100 text-green-800',
            'sakit' => 'bg-yellow-100 text-yellow-800',
            'izin' => 'bg-blue-100 text-blue-800',
            'cuti' => 'bg-purple-100 text-purple-800',
            'dinas_dalam' => 'bg-indigo-100 text-indigo-800',
            'dinas_luar' => 'bg-cyan-100 text-cyan-800',
            'alpha' => 'bg-red-100 text-red-800',
            'libur' => 'bg-gray-100 text-gray-800',
        ];

        $color = $colors[$attendance->status] ?? 'bg-gray-100 text-gray-800';
        $displayText = self::formatAttendanceText($attendance);

        return "<span class='inline-flex items-center px-1 py-1 rounded text-xs font-medium {$color}' style='font-size: 10px; line-height: 1.2;'>{$displayText}</span>";
    }

    private static function formatAttendanceText(Attendance $attendance): string
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
                $text .= $attendance->check_in->format('Hi');
            }

            if ($attendance->check_out) {
                $text .= '<br>' . $attendance->check_out->format('Hi:s');
            } elseif ($attendance->check_in) {
                $text .= '<br>TPP';
            }

            return $text ?: 'H';
        }

        // For other statuses (sakit, izin, cuti, dinas)
        $codes = Attendance::getStatusCodes();
        return $codes[$attendance->status] ?? 'TK';
    }

    private static function getAttendanceTooltip(int $employeeId, Carbon $date): string
    {
        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', $date)
            ->first();

        if (!$attendance) {
            if ($date->isWeekend()) {
                return $date->format('d M Y') . ' - Libur (Weekend)';
            }
            return $date->format('d M Y') . ' - Tidak ada data presensi';
        }

        $tooltip = $date->format('d M Y') . ' - ' . ucfirst(str_replace('_', ' ', $attendance->status));
        
        if ($attendance->check_in) {
            $tooltip .= "\nMasuk: " . $attendance->check_in->format('H:i');
        }
        
        if ($attendance->check_out) {
            $tooltip .= "\nPulang: " . $attendance->check_out->format('H:i:s');
            
            if ($attendance->check_in) {
                $diff = $attendance->check_out->diff($attendance->check_in);
                $tooltip .= "\nLama kerja: " . $diff->format('%h:%I');
            }
        }
        
        if ($attendance->notes) {
            $tooltip .= "\nCatatan: " . $attendance->notes;
        }

        return $tooltip;
    }

    /**
     * Get attendance statistics for the month
     */
    public static function getMonthlyStats(int $year, int $month): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth()->endOfDay();
        
        return [
            'total_employees' => Employee::count(),
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
    }
}