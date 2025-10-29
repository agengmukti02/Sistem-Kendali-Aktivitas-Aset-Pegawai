<?php

namespace App\Filament\Resources\Employeeschedules\Pages;

use App\Filament\Resources\Employeeschedules\EmployeescheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeschedule extends EditRecord
{
    protected static string $resource = EmployeescheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
