<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonthlyAttendanceResource\Pages;
use App\Filament\Resources\Attendances\Tables\MonthlyAttendanceTable;
use App\Models\Employee;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class MonthlyAttendanceResource extends Resource
{
    protected static ?string $model = Employee::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-calendar';
    }

    public static function getNavigationLabel(): string
    {
        return 'Rekap Vertikal';
    }

    public static function getModelLabel(): string
    {
        return 'Rekap Presensi Vertikal';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'Rekap Presensi Vertikal';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Presensi';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function table(Table $table): Table
    {
        // Get month/year from request or use current
        $month = request('tableFilters.month.value', now()->month);
        $year = request('tableFilters.year.value', now()->year);
        
        return MonthlyAttendanceTable::configure($table, $year, $month);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMonthlyAttendance::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canView($record): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $stats = MonthlyAttendanceTable::getMonthlyStats(now()->year, now()->month);
        return $stats['total_employees'];
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
}