<?php

namespace App\Filament\Resources\MonthlyAttendanceResource\Pages;

use App\Filament\Resources\MonthlyAttendanceResource;
use App\Filament\Resources\Attendances\Tables\MonthlyAttendanceTable;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Tables\Table;
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
            Actions\Action::make('export')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    // TODO: Implement Excel export
                    $this->notify('info', 'Fitur export akan segera tersedia');
                }),
                
            Actions\Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->action(function () {
                    // TODO: Implement print view
                    $this->notify('info', 'Fitur print akan segera tersedia');
                }),
                
            Actions\Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    $this->redirect(request()->fullUrl());
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