<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static bool $isDiscoverable = false;
    
    protected function getStats(): array
    {
        $totalPegawai = Employee::count();
        $pegawaiAktif = Employee::where('status', 'aktif')->count();
        $pegawaiNonAktif = Employee::where('status', 'non-aktif')->count();

        return [
            Stat::make('Total Pegawai', $totalPegawai)
                ->icon('heroicon-o-user-group')
                ->color('primary'),
            Stat::make('Pegawai Aktif', $pegawaiAktif)
                ->icon('heroicon-o-check-badge')
                ->color('success'),
            Stat::make('Pegawai Non-Aktif', $pegawaiNonAktif)
                ->icon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}