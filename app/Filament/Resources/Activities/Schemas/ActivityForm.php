<?php

namespace App\Filament\Resources\Activities\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;


class ActivityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->required()
                    ->searchable(),
                Select::make('jenis')
                    ->options([
                        'dinas'=>'Dinas',
                        'dinas_luar'=>'Dinas Luar',
                        'cuti'=>'Cuti',
                        'diklat'=>'Diklat',
                        'tugas_belajar'=>'Tugas Belajar',
                        'lainnya'=>'Lainnya',
                    ])
                    ->required(),
                DatePicker::make('tanggal_awal')
                    ->required(),
                DatePicker::make('tanggal_akhir'),
                TextInput::make('nomor_surat')
                    ->label('Nomor Surat'),
                DatePicker::make('tanggal_surat')
                    ->label('Tanggal Surat'),
                TextInput::make('keperluan')
                    ->required(),
                Textarea::make('uraian')
                    ->rows(3),
                Select::make('status')
                    ->options([
                        'pending'=>'Pending',
                        'disetujui'=>'Disetujui',
                        'ditolak'=>'Ditolak',
                    ])
                    ->default('pending')
                    ->visible(fn () => auth()->check()),
                    // ->visible(fn () => auth()->user()?->hasAnyRole(['admin', 'operator'])),
                Select::make('approved_by_id')
                    ->relationship('approvedBy', 'name')
                    ->disabled()
                    ->visible(fn ($record) => $record && $record->status !== 'pending')
            ]);
    }
}
