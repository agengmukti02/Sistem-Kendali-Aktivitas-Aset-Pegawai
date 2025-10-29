<?php

namespace App\Filament\Resources\MonthlyAttendanceResource\Pages;

use App\Filament\Resources\MonthlyAttendanceResource;
use App\Filament\Resources\Attendances\Tables\MonthlyAttendanceTable;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class ListMonthlyAttendance extends ListRecords
{
    protected static string $resource = MonthlyAttendanceResource::class;

    protected static ?string $title = 'Presensi Bulanan Pegawai';

    public function getSubheading(): ?string
    {
        $month = request('tableFilters.month.value', now()->month);
        $year = request('tableFilters.year.value', now()->year);
        
        $monthName = Carbon::create($year, $month, 1)->format('F Y');
        $stats = MonthlyAttendanceTable::getMonthlyStats($year, $month);
        
        return "Laporan presensi bulan {$monthName} • {$stats['total_employees']} Pegawai • {$stats['working_days']} Hari Kerja";
    }

    public function table(Table $table): Table
    {
        $month = request('tableFilters.month.value', now()->month);
        $year = request('tableFilters.year.value', now()->year);
        
        return MonthlyAttendanceTable::configure($table, $year, $month);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_report')
                ->label('Lihat Laporan Web')
                ->icon('heroicon-o-document-text')
                ->color('info')
                ->url(function () {
                    $month = request('tableFilters.month.value', now()->month);
                    $year = request('tableFilters.year.value', now()->year);
                    return route('attendance.report', ['year' => $year, 'month' => $month]);
                })
                ->openUrlInNewTab(),
            
            Actions\Action::make('export')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->url(function () {
                    $month = request('tableFilters.month.value', now()->month);
                    $year = request('tableFilters.year.value', now()->year);
                    return route('attendance.export-excel', ['year' => $year, 'month' => $month]);
                })
                ->openUrlInNewTab(),
                
            Actions\Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(function () {
                    $month = request('tableFilters.month.value', now()->month);
                    $year = request('tableFilters.year.value', now()->year);
                    return route('attendance.print', ['year' => $year, 'month' => $month]);
                })
                ->openUrlInNewTab(),
                
            Actions\Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    Notification::make()
                        ->title('Data berhasil direfresh')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // TODO: Add attendance summary widgets
        ];
    }
}