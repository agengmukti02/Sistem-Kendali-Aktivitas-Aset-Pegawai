<?php

namespace App\Filament\Resources\Employees;

use App\Filament\Resources\Employees\Pages\CreateEmployee;
use App\Filament\Resources\Employees\Pages\EditEmployee;
use App\Filament\Resources\Employees\Pages\ListEmployees;
use App\Filament\Resources\Employees\Schemas\EmployeeForm;
use App\Filament\Resources\Employees\Tables\EmployeesTable;
use App\Models\Employee;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-users';
    protected static string | UnitEnum | null $navigationGroup = 'Manajemen Pegawai';

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view employees');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create employees');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('edit employees');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete employees');
    }

    public static function form(Schema $schema): Schema
    {
        return EmployeeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeesTable::configure($table);
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
            'index' => ListEmployees::route('/'),
            'create' => CreateEmployee::route('/create'),
            'edit' => EditEmployee::route('/{record}/edit'),
        ];
    }
}
