<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\RecentEmployeesTableWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use Filament\Pages\Page;

class DashboardAtasan extends Page
{
    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-home';
    }

    public static function getNavigationLabel(): string
    {
        return 'Dashboard Atasan';
    }

    public function getTitle(): string
    {
        return 'Dashboard Manajemen Pegawai';
    }

    public function getView(): string
    {
        return 'filament.pages.dashboard-atasan';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Fitur';
    }

    protected function getColumns(): int | array
    {
        return 2;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
        ];
    }

    protected function getBodyWidgets(): array
    {
        return [
            RecentEmployeesTableWidget::class,
        ];
    }
}