<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('informasi pegawai')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('nip')
                        ->label('NIP')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('golongan')
                        ->label('Golongan')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('jabatan')
                        ->label('Jabatan')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('status')
                        ->label('Status')
                        ->required()
                        ->maxLength(255),
                ])->columns(2),


                Section::make('kontak pegawai')
                ->schema([
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    TextInput::make('phone')
                        ->label('No. Telepon')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                ])->columns(2),

                Section::make('status pegawai')
                ->schema([
                    Select::make('attendance_status')
                        ->label('Status Kehadiran')
                        ->options([
                            'dinas' => 'Dinas dalam',
                            'luar' => 'Dinas luar',
                            'cuti' => 'Cuti',
                            'sakit' => 'Sakit',
                            'izin' => 'Izin',
                            'alpa' => 'Alpa',
                        ])
                ])->columns(2)

            ]);
    }
}
