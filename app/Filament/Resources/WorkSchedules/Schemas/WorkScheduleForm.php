<?php

namespace App\Filament\Resources\WorkSchedules\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Schemas\Schema;
use App\Models\Employee;

class WorkScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('employee_id')
                                    ->label('Pegawai')
                                    ->relationship('employee', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->getOptionLabelFromRecordUsing(fn (Employee $record): string => "{$record->name} ({$record->nip})"),
                                    
                                DatePicker::make('work_date')
                                    ->label('Tanggal Kerja')
                                    ->required()
                                    ->default(now())
                                    ->displayFormat('d/m/Y')
                                    ->closeOnDateSelection(),
                            ]),
                    ]),

                Section::make('Jadwal & Status')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('shift')
                                    ->label('Shift')
                                    ->options([
                                        'Pagi' => '🌅 Pagi (07:00-15:00)',
                                        'Siang' => '🌞 Siang (15:00-23:00)',
                                        'Malam' => '🌙 Malam (23:00-07:00)',
                                    ])
                                    ->placeholder('Pilih Shift'),
                                    
                                Select::make('status')
                                    ->label('Status Kehadiran')
                                    ->options([
                                        'Hadir' => '✅ Hadir',
                                        'Dinas Dalam' => '🏢 Dinas Dalam',
                                        'Dinas Luar' => '📍 Dinas Luar',
                                        'Cuti' => '📅 Cuti',
                                        'Izin' => '✋ Izin',
                                        'Sakit' => '😷 Sakit',
                                        'Alpha' => '❌ Alpha',
                                        'Libur' => '🏠 Libur',
                                    ])
                                    ->required()
                                    ->default('Hadir')
                                    ->native(false),
                            ]),
                    ]),

                Section::make('Waktu Kehadiran')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DateTimePicker::make('check_in')
                                    ->label('Waktu Masuk')
                                    ->displayFormat('d/m/Y H:i')
                                    ->seconds(false),
                                    
                                DateTimePicker::make('check_out')
                                    ->label('Waktu Keluar')
                                    ->displayFormat('d/m/Y H:i')
                                    ->seconds(false)
                                    ->after('check_in'),
                            ]),
                    ])
                    ->visible(fn ($get) => in_array($get('status'), ['Hadir', 'Dinas Dalam', 'Dinas Luar'])),

                Section::make('Catatan')
                    ->schema([
                        TextInput::make('reason')
                            ->label('Alasan')
                            ->placeholder('Contoh: Sakit demam, Cuti tahunan, dll'),
                            
                        Textarea::make('notes')
                            ->label('Catatan Tambahan')
                            ->placeholder('Catatan tambahan tentang jadwal kerja ini...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
