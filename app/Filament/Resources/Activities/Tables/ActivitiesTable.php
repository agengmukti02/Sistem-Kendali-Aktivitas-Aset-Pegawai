<?php

namespace App\Filament\Resources\Activities\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables;
use Filament\Actions\Action;


class ActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Pegawai')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('jenis')
                    ->searchable(),
                TextColumn::make('tanggal_awal')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_akhir')
                    ->date()
                    ->sortable(),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'disetujui', 
                        'danger' => 'ditolak',
                    ]),
                TextColumn::make('keperluan')
                    ->searchable()
                    ->limit(50),
            ])
            ->filters([
                SelectFilter::make('jenis')
                    ->options([
                        'dinas'=>'Dinas',
                        'dinas_luar'=>'Dinas Luar',
                        'cuti'=>'Cuti',
                        'diklat'=>'Diklat',
                        'lainnya'=>'Lainnya',
                    ]),
            ])
            ->recordActions([
                Action::make('di_setujui')
                    ->label('Di Setujui')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'disetujui',
                            'approved_by_id' => auth()->id(),
                        ]);

                        // Create a simple document record for now
                        if (in_array($record->jenis, ['dinas','diklat'])) {
                            $filename = 'SPT_'.$record->id.'.pdf';
                            
                            $record->documents()->create([
                                'path' => 'documents/'.$filename,
                                'jenis_dokumen' => 'SPT',
                                'filename' => $filename,
                            ]);
                        }
                    })
                    ->color('success'),

                Action::make('di_tolak')
                    ->label('Di Tolak')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'ditolak',
                            'approved_by_id' => auth()->id(),
                        ]);
                    })
                    ->color('danger'),
                    
                Action::make('download')
                    ->label('Download SPT')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn ($record) => $record->documents()->where('jenis_dokumen', 'SPT')->exists())
                    ->url(fn ($record) => route('documents.download', $record->documents()->where('jenis_dokumen', 'SPT')->first()->id))
                    ->openUrlInNewTab(),
                    
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
