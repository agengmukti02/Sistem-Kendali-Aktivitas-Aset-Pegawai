<?php

namespace App\Filament\Resources\Employeeschedules;

use App\Filament\Resources\Employeeschedules\Pages\CreateEmployeeschedule;
use App\Filament\Resources\Employeeschedules\Pages\EditEmployeeschedule;
use App\Filament\Resources\Employeeschedules\Pages\ListEmployeeschedules;
use App\Filament\Resources\Employeeschedules\Schemas\EmployeescheduleForm;
use App\Filament\Resources\Employeeschedules\Tables\EmployeeschedulesTable;
use App\Models\Employeeschedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EmployeescheduleResource extends Resource
{
    protected static ?string $model = Employeeschedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return EmployeescheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeschedulesTable::configure($table);
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
            'index' => ListEmployeeschedules::route('/'),
            'create' => CreateEmployeeschedule::route('/create'),
            'edit' => EditEmployeeschedule::route('/{record}/edit'),
        ];
    }
}
