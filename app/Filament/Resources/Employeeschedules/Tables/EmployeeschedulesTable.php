<?php

namespace App\Filament\Resources\Employeeschedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action;
use Filament\Tables\Table;
use App\Models\Employee;
use App\Models\WorkSchedule;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class EmployeeschedulesTable
{
    /**
     * Generate jadwal mingguan otomatis untuk pegawai
     */
    public static function generateWeeklySchedule(int $employeeId, Carbon $referenceDate): int
    {
        try {
            $startOfWeek = $referenceDate->copy()->startOfWeek(Carbon::MONDAY);
            
            // Verify employee exists
            $employee = Employee::find($employeeId);
            if (!$employee) {
                throw new \Exception("Employee with ID {$employeeId} not found");
            }
            
            // Default schedule pattern (weekday work, weekend off)
            $weeklyPattern = [
                1 => ['shift' => 'Pagi', 'status' => 'Hadir'],    // Monday
                2 => ['shift' => 'Pagi', 'status' => 'Hadir'],    // Tuesday  
                3 => ['shift' => 'Pagi', 'status' => 'Hadir'],    // Wednesday
                4 => ['shift' => 'Pagi', 'status' => 'Hadir'],    // Thursday
                5 => ['shift' => 'Pagi', 'status' => 'Hadir'],    // Friday
                6 => ['shift' => 'Pagi', 'status' => 'Hadir'],    // Saturday (Half day)
                0 => ['shift' => null, 'status' => 'Libur'],      // Sunday
            ];
            
            $createdCount = 0;
            
            for ($i = 0; $i < 7; $i++) {
                $date = $startOfWeek->copy()->addDays($i);
                $dayOfWeek = $date->dayOfWeek; // 0=Sunday, 1=Monday, etc.
                $pattern = $weeklyPattern[$dayOfWeek];
                
                // Skip if schedule already exists
                if (WorkSchedule::where('employee_id', $employeeId)
                        ->whereDate('work_date', $date)
                        ->exists()) {
                    continue;
                }
                
                WorkSchedule::create([
                    'employee_id' => $employeeId,
                    'work_date' => $date,
                    'shift' => $pattern['shift'],
                    'status' => $pattern['status'],
                    'check_in' => null,
                    'check_out' => null,
                    'reason' => $pattern['status'] === 'Libur' ? 'Weekend' : null,
                ]);
                
                $createdCount++;
            }
            
            return $createdCount;
            
        } catch (\Exception $e) {
            \Log::error('Error generating weekly schedule', [
                'employee_id' => $employeeId,
                'reference_date' => $referenceDate->toDateString(),
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public static function configure(Table $table): Table
    {
        return $table
            ->query(WorkSchedule::with(['employee']))
            ->columns([
                // Identitas Pegawai
                TextColumn::make('employee.nip')
                    ->label('NIP')
                    ->sortable()
                    ->searchable()
                    ->weight('semibold')
                    ->copyable()
                    ->tooltip('Click to copy'),
                    
                TextColumn::make('employee.name')
                    ->label('Nama Pegawai')
                    ->sortable()
                    ->searchable()
                    ->weight('semibold')
                    ->wrap(),

                // Jadwal Kerja
                TextColumn::make('work_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->searchable()
                    ->description(fn (WorkSchedule $record): string => $record->work_date->format('l')),
                    
                TextColumn::make('shift')
                    ->label('Shift')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Pagi' => 'success',
                        'Siang' => 'warning', 
                        'Malam' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => $state ?? 'Libur'),

                // Status Kehadiran dengan Badge Color Coding yang diperbaiki
                TextColumn::make('status')
                    ->label('Status Kehadiran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Dinas Dalam' => 'primary',
                        'Dinas Luar' => 'info',
                        'Cuti', 'Izin' => 'warning',
                        'Sakit', 'Alpha' => 'danger',
                        'Libur' => 'gray',
                        default => 'secondary',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Hadir' => 'heroicon-o-check-circle',
                        'Dinas Dalam' => 'heroicon-o-building-office',
                        'Dinas Luar' => 'heroicon-o-map-pin',
                        'Cuti' => 'heroicon-o-calendar-days',
                        'Izin' => 'heroicon-o-hand-raised',
                        'Sakit' => 'heroicon-o-face-frown',
                        'Alpha' => 'heroicon-o-x-circle',
                        'Libur' => 'heroicon-o-home',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->sortable(),

                // Waktu Kehadiran
                TextColumn::make('check_in')
                    ->label('Check In')
                    ->time('H:i')
                    ->placeholder('-')
                    ->tooltip('Waktu masuk kerja'),
                    
                TextColumn::make('check_out')
                    ->label('Check Out')
                    ->time('H:i')
                    ->placeholder('-')
                    ->tooltip('Waktu pulang kerja'),

                // Working Hours (calculated)
                TextColumn::make('working_hours')
                    ->label('Jam Kerja')
                    ->getStateUsing(function (WorkSchedule $record): string {
                        if ($record->check_in && $record->check_out) {
                            $diff = $record->check_out->diff($record->check_in);
                            return $diff->format('%h:%I');
                        }
                        return '-';
                    })
                    ->badge()
                    ->color(fn (string $state): string => $state === '-' ? 'gray' : 'info'),

                // Optimized Weekly Schedule
                TextColumn::make('weekly_schedule')
                    ->label('Jadwal Minggu Ini')
                    ->html()
                    ->getStateUsing(function (WorkSchedule $record) {
                        try {
                            // Cache key for performance
                            $cacheKey = "weekly_schedule_{$record->employee_id}_{$record->work_date->format('Y-W')}";
                            
                            return cache()->remember($cacheKey, 300, function () use ($record) {
                                $startOfWeek = $record->work_date->copy()->startOfWeek(Carbon::MONDAY);
                                $endOfWeek = $startOfWeek->copy()->addDays(6);
                                
                                $schedules = WorkSchedule::where('employee_id', $record->employee_id)
                                    ->whereBetween('work_date', [$startOfWeek, $endOfWeek])
                                    ->orderBy('work_date')
                                    ->get()
                                    ->keyBy(function ($item) {
                                        return $item->work_date->dayOfWeek; // 0=Sunday, 1=Monday
                                    });

                                $days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                                $html = '<div class="flex gap-1 flex-wrap">';
                                
                                for ($i = 1; $i <= 7; $i++) {
                                    $dayIndex = $i === 7 ? 0 : $i; // Convert to Carbon dayOfWeek
                                    $daySchedule = $schedules->get($dayIndex);
                                    $dayName = $days[$dayIndex];
                                    
                                    if ($daySchedule) {
                                        $colors = [
                                            'Hadir' => 'bg-green-100 text-green-800 border-green-200',
                                            'Dinas Dalam' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'Dinas Luar' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                            'Cuti' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'Izin' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'Sakit' => 'bg-red-100 text-red-800 border-red-200',
                                            'Alpha' => 'bg-red-100 text-red-800 border-red-200',
                                            'Libur' => 'bg-gray-100 text-gray-800 border-gray-200',
                                        ];
                                        
                                        $color = $colors[$daySchedule->status] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                                        $isToday = $daySchedule->work_date->isToday() ? 'ring-2 ring-blue-400' : '';
                                        
                                        $tooltip = "{$daySchedule->work_date->format('d M')}: {$daySchedule->status}";
                                        if ($daySchedule->shift) {
                                            $tooltip .= " ({$daySchedule->shift})";
                                        }
                                        
                                        $html .= "<span class='px-2 py-1 text-xs rounded border {$color} {$isToday}' title='{$tooltip}'>{$dayName}</span>";
                                    } else {
                                        $html .= "<span class='px-2 py-1 text-xs rounded border bg-gray-50 text-gray-400 border-gray-100' title='Belum dijadwalkan'>{$dayName}</span>";
                                    }
                                }
                                
                                $html .= '</div>';
                                return $html;
                            });
                        } catch (\Exception $e) {
                            \Log::error('Error generating weekly schedule display', [
                                'record_id' => $record->id,
                                'error' => $e->getMessage()
                            ]);
                            return '<span class="text-red-500">Error loading</span>';
                        }
                    })
                    ->searchable(false)
                    ->sortable(false)
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
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
                    ->multiple()
                    ->placeholder('Semua Status'),
                    
                SelectFilter::make('shift')
                    ->label('Shift Kerja')
                    ->options([
                        'Pagi' => '🌅 Pagi (07:00-15:00)',
                        'Siang' => '🌞 Siang (15:00-23:00)', 
                        'Malam' => '🌙 Malam (23:00-07:00)',
                    ])
                    ->placeholder('Semua Shift'),
                    
                SelectFilter::make('employee_id')
                    ->label('Pegawai')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Semua Pegawai')
                    ->multiple(),

                Filter::make('work_date')
                    ->label('Filter Tanggal')
                    ->form([
                        DatePicker::make('work_date_from')
                            ->label('Dari Tanggal')
                            ->displayFormat('d/m/Y')
                            ->native(false),
                        DatePicker::make('work_date_until')
                            ->label('Sampai Tanggal')
                            ->displayFormat('d/m/Y')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['work_date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('work_date', '>=', $date),
                            )
                            ->when(
                                $data['work_date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('work_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['work_date_from'] ?? null) {
                            $indicators[] = 'Dari: ' . Carbon::parse($data['work_date_from'])->format('d M Y');
                        }
                        if ($data['work_date_until'] ?? null) {
                            $indicators[] = 'Sampai: ' . Carbon::parse($data['work_date_until'])->format('d M Y');
                        }
                        return $indicators;
                    }),

                SelectFilter::make('week')
                    ->label('Filter Minggu')
                    ->options(function () {
                        $weeks = [];
                        $start = now()->subWeeks(4);
                        for ($i = 0; $i < 8; $i++) {
                            $weekStart = $start->copy()->addWeeks($i)->startOfWeek();
                            $weekEnd = $weekStart->copy()->endOfWeek();
                            $weeks[$weekStart->format('Y-W')] = 
                                $weekStart->format('d M') . ' - ' . $weekEnd->format('d M Y');
                        }
                        return $weeks;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        if (!empty($data['value'])) {
                            [$year, $week] = explode('-W', $data['value']);
                            $startOfWeek = Carbon::createFromFormat('Y-W', $data['value'])->startOfWeek();
                            $endOfWeek = $startOfWeek->copy()->endOfWeek();
                            return $query->whereBetween('work_date', [$startOfWeek, $endOfWeek]);
                        }
                        return $query;
                    }),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->url(fn (WorkSchedule $record): string => route('filament.admin.resources.work-schedules.edit', $record))
                    ->tooltip('Edit jadwal kerja'),
                    
                Action::make('view')
                    ->label('Lihat Detail')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalContent(fn (WorkSchedule $record) => view('filament.pages.work-schedule-detail', ['record' => $record]))
                    ->modalHeading(fn (WorkSchedule $record) => "Detail Jadwal - {$record->employee->name}")
                    ->modalWidth('2xl')
                    ->modalCancelActionLabel('Tutup')
                    ->modalSubmitAction(false),
                    
                Action::make('attendance')
                    ->label('Presensi')
                    ->icon('heroicon-o-clock')
                    ->color('success')
                    ->action(function (WorkSchedule $record) {
                        $now = Carbon::now();
                        
                        try {
                            if (!$record->check_in) {
                                $record->update(['check_in' => $now]);
                                
                                Notification::make()
                                    ->title('Check In Berhasil')
                                    ->body("Presensi masuk pada {$now->format('H:i')}")
                                    ->success()
                                    ->send();
                                    
                            } elseif (!$record->check_out && $record->check_in->diffInHours($now) >= 1) {
                                $record->update(['check_out' => $now]);
                                
                                $workingHours = $record->check_in->diff($now);
                                
                                Notification::make()
                                    ->title('Check Out Berhasil')
                                    ->body("Presensi keluar pada {$now->format('H:i')} (Durasi: {$workingHours->format('%h:%I')})")
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Presensi Gagal')
                                    ->body('Minimal 1 jam sejak check in untuk check out')
                                    ->warning()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error')
                                ->body('Gagal melakukan presensi: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Presensi')
                    ->modalDescription(fn (WorkSchedule $record) => 
                        !$record->check_in 
                            ? 'Lakukan check in sekarang?' 
                            : (!$record->check_out ? 'Lakukan check out sekarang?' : 'Presensi sudah lengkap')
                    )
                    ->visible(fn (WorkSchedule $record) => 
                        $record->work_date->isToday() && 
                        in_array($record->status, ['Hadir', 'Dinas Dalam', 'Dinas Luar']) &&
                        (!$record->check_out)
                    ),

                Action::make('quick_status')
                    ->label('Ubah Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->form([
                        Select::make('status')
                            ->label('Status Baru')
                            ->options([
                                'Hadir' => '✅ Hadir',
                                'Dinas Dalam' => '🏢 Dinas Dalam',
                                'Dinas Luar' => '📍 Dinas Luar',
                                'Cuti' => '📅 Cuti',
                                'Izin' => '✋ Izin',
                                'Sakit' => '😷 Sakit',
                                'Alpha' => '❌ Alpha',
                            ])
                            ->required()
                            ->native(false),
                    ])
                    ->action(function (WorkSchedule $record, array $data) {
                        $record->update(['status' => $data['status']]);
                        
                        Notification::make()
                            ->title('Status Diperbarui')
                            ->body("Status berhasil diubah ke: {$data['status']}")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    Action::make('delete')
                        ->label('Hapus Terpilih')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Jadwal Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus jadwal kerja yang dipilih?')
                        ->action(function (Collection $records) {
                            $records->each->delete();
                            
                            Notification::make()
                                ->title('Jadwal Berhasil Dihapus')
                                ->body("Berhasil menghapus {$records->count()} jadwal kerja")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                        
                    Action::make('generate_weekly_schedule')
                        ->label('Generate Jadwal Mingguan')
                        ->icon('heroicon-o-calendar-days')
                        ->color('primary')
                        ->form([
                            DatePicker::make('reference_date')
                                ->label('Tanggal Referensi')
                                ->default(now())
                                ->required()
                                ->helperText('Sistem akan generate jadwal untuk minggu yang mengandung tanggal ini'),
                        ])
                        ->action(function (array $data, Collection $records) {
                            $referenceDate = Carbon::parse($data['reference_date']);
                            $successCount = 0;
                            $errorCount = 0;
                            $errors = [];
                            
                            // Group by employee to avoid duplicates
                            $employeeIds = $records->pluck('employee_id')->unique();
                            
                            foreach ($employeeIds as $employeeId) {
                                try {
                                    $created = self::generateWeeklySchedule($employeeId, $referenceDate);
                                    $successCount += $created;
                                } catch (\Exception $e) {
                                    $errorCount++;
                                    $errors[] = "Employee ID {$employeeId}: " . $e->getMessage();
                                }
                            }
                            
                            if ($successCount > 0) {
                                Notification::make()
                                    ->title('Jadwal Berhasil Dibuat')
                                    ->body("Berhasil membuat {$successCount} jadwal untuk {$employeeIds->count()} pegawai")
                                    ->success()
                                    ->send();
                            }
                            
                            if ($errorCount > 0) {
                                Notification::make()
                                    ->title('Beberapa Jadwal Gagal Dibuat')
                                    ->body("Gagal: {$errorCount} pegawai. " . implode('; ', array_slice($errors, 0, 2)))
                                    ->warning()
                                    ->send();
                            }
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Generate Jadwal Mingguan')
                        ->modalDescription('Sistem akan membuat jadwal kerja untuk seminggu penuh untuk setiap pegawai yang dipilih.')
                        ->deselectRecordsAfterCompletion(),
                        
                    Action::make('bulk_status_update')
                        ->label('Update Status Massal')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->form([
                            Select::make('status')
                                ->label('Status Baru')
                                ->options([
                                    'Hadir' => '✅ Hadir',
                                    'Dinas Dalam' => '🏢 Dinas Dalam',
                                    'Dinas Luar' => '📍 Dinas Luar',
                                    'Cuti' => '📅 Cuti',
                                    'Izin' => '✋ Izin',
                                    'Sakit' => '😷 Sakit',
                                    'Alpha' => '❌ Alpha',
                                ])
                                ->required()
                                ->native(false),
                        ])
                        ->action(function (array $data, Collection $records) {
                            $count = $records->count();
                            
                            try {
                                foreach ($records as $record) {
                                    $record->update(['status' => $data['status']]);
                                }
                                
                                Notification::make()
                                    ->title('Status Berhasil Diperbarui')
                                    ->body("Berhasil mengubah status {$count} jadwal ke: {$data['status']}")
                                    ->success()
                                    ->send();
                                    
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Error Update Status')
                                    ->body('Gagal mengupdate status: ' . $e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Update Status Massal')
                        ->modalDescription(fn (Collection $records) => 
                            "Mengubah status untuk {$records->count()} jadwal kerja yang dipilih"
                        )
                        ->deselectRecordsAfterCompletion(),

                    Action::make('bulk_copy_schedule')
                        ->label('Salin Jadwal')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('info')
                        ->form([
                            DatePicker::make('target_date')
                                ->label('Tanggal Target')
                                ->required()
                                ->after('today')
                                ->helperText('Jadwal akan disalin ke tanggal ini'),
                        ])
                        ->action(function (array $data, Collection $records) {
                            $targetDate = Carbon::parse($data['target_date']);
                            $successCount = 0;
                            
                            foreach ($records as $record) {
                                // Skip if target schedule already exists
                                if (WorkSchedule::where('employee_id', $record->employee_id)
                                        ->whereDate('work_date', $targetDate)
                                        ->exists()) {
                                    continue;
                                }
                                
                                WorkSchedule::create([
                                    'employee_id' => $record->employee_id,
                                    'work_date' => $targetDate,
                                    'shift' => $record->shift,
                                    'status' => $record->status,
                                    'reason' => 'Copied from ' . $record->work_date->format('Y-m-d'),
                                ]);
                                
                                $successCount++;
                            }
                            
                            Notification::make()
                                ->title('Jadwal Berhasil Disalin')
                                ->body("Berhasil menyalin {$successCount} jadwal ke {$targetDate->format('d M Y')}")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('work_date', 'desc')
            ->paginated([10, 25, 50, 100])
            ->paginationPageOptions([10, 25, 50, 100])
            ->poll('30s') // Auto refresh setiap 30 detik
            ->striped()
            ->emptyStateHeading('Tidak ada jadwal kerja')
            ->emptyStateDescription('Belum ada jadwal kerja yang dibuat. Gunakan tombol "Generate Jadwal Mingguan" untuk membuat jadwal otomatis.')
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->persistFiltersInSession()
            ->persistSortInSession()
            ->deferLoading()
            ->extremePaginationLinks();
    }

    /**
     * Get schedule statistics for dashboard
     */
    public static function getScheduleStats(): array
    {
        $today = now()->format('Y-m-d');
        $thisWeek = [now()->startOfWeek(), now()->endOfWeek()];
        
        return [
            'today_present' => WorkSchedule::whereDate('work_date', $today)
                ->where('status', 'Hadir')
                ->whereNotNull('check_in')
                ->count(),
            'today_total' => WorkSchedule::whereDate('work_date', $today)
                ->whereIn('status', ['Hadir', 'Dinas Dalam', 'Dinas Luar'])
                ->count(),
            'week_schedules' => WorkSchedule::whereBetween('work_date', $thisWeek)
                ->count(),
            'pending_checkout' => WorkSchedule::whereDate('work_date', $today)
                ->whereNotNull('check_in')
                ->whereNull('check_out')
                ->whereIn('status', ['Hadir', 'Dinas Dalam', 'Dinas Luar'])
                ->count(),
        ];
    }
}
