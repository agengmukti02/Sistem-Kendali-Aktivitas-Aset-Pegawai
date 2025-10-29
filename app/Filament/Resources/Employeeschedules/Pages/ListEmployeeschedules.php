<?php

namespace App\Filament\Resources\Employeeschedules\Pages;

use App\Filament\Resources\Employeeschedules\EmployeescheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeschedules extends ListRecords
{
    protected static string $resource = EmployeescheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
