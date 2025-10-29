<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentEmployeesTableWidget extends BaseWidget
{
    protected static bool $isDiscoverable = false;
    
    protected static ?string $heading = 'Pegawai Terbaru';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Employee::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->copyable()
                    ->weight('semibold'),
                TextColumn::make('name')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->weight('semibold'),
                TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('golongan')
                    ->label('Golongan')
                    ->badge()
                    ->color('info'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'nonaktif' => 'danger',
                        default => 'secondary',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'aktif' => 'heroicon-o-check-circle',
                        'nonaktif' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    }),
                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->tooltip(fn (Employee $record): string => $record->created_at->diffForHumans()),
            ])
            ->paginated(false)
            ->defaultSort('created_at', 'desc');
    }
}