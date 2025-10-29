<?php

namespace App\Filament\Resources\HorizontalAttendanceResource\Pages;

use App\Filament\Resources\HorizontalAttendanceResource;
use App\Filament\Resources\Attendances\Tables\HorizontalAttendanceTable;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Tables\Table;
use Carbon\Carbon;

class ListHorizontalAttendance extends ListRecords
{
    protected static string $resource = HorizontalAttendanceResource::class;

    protected static ?string $title = 'Rekap Presensi Horizontal';

    public function getSubheading(): ?string
    {
        $month = request('tableFilters.month.value', now()->month);
        $year = request('tableFilters.year.value', now()->year);
        
        $monthName = Carbon::create($year, $month, 1)->format('F Y');
        $stats = HorizontalAttendanceTable::getMonthlyStats($year, $month);
        
        return "📊 {$monthName} • {$stats['total_employees']} Pegawai • {$stats['attendance_rate']}% Tingkat Kehadiran • {$stats['perfect_attendance']} Kehadiran Sempurna";
    }

    public function table(Table $table): Table
    {
        $month = request('tableFilters.month.value', now()->month);
        $year = request('tableFilters.year.value', now()->year);
        
        return HorizontalAttendanceTable::configure($table, $year, $month);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('legend')
                ->label('Keterangan Warna')
                ->icon('heroicon-o-information-circle')
                ->color('info')
                ->modalHeading('📋 Keterangan Status Presensi')
                ->modalContent(view('filament.components.attendance-legend'))
                ->modalWidth('md')
                ->modalCancelActionLabel('Tutup')
                ->modalSubmitAction(false),

            Actions\Action::make('export_excel')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $month = request('tableFilters.month.value', now()->month);
                    $year = request('tableFilters.year.value', now()->year);
                    
                    // TODO: Implement CSV export with horizontal layout
                    $this->notify('info', 'Fitur export CSV akan segera tersedia');
                })
                ->requiresConfirmation()
                ->modalHeading('Export ke CSV')
                ->modalDescription('Export rekap presensi dalam format CSV horizontal'),
                
            Actions\Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->action(function () {
                    // TODO: Implement print-friendly view
                    $this->notify('info', 'Fitur print akan segera tersedia');
                }),

            Actions\Action::make('fullscreen')
                ->label('Mode Fullscreen')
                ->icon('heroicon-o-arrows-pointing-out')
                ->color('secondary')
                ->action(function () {
                    $this->js('
                        if (document.fullscreenElement) {
                            document.exitFullscreen();
                        } else {
                            document.documentElement.requestFullscreen();
                        }
                    ');
                }),
                
            Actions\Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    // Refresh will happen automatically after action
                }),
        ];
    }

    public function getFooter(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.components.attendance-footer');
    }

    /**
     * Custom CSS for horizontal scrolling
     */
    protected function getViewData(): array
    {
        return [
            'customStyles' => '
                <style>
                    /* Horizontal scroll optimization */
                    .fi-ta-table-container {
                        overflow-x: auto;
                        max-width: 100vw;
                    }
                    
                    /* Sticky columns styling */
                    .sticky {
                        position: sticky;
                        background: white;
                        z-index: 10;
                    }
                    
                    /* Day column optimization */
                    .day-column {
                        min-width: 40px !important;
                        max-width: 40px !important;
                        text-align: center;
                        padding: 8px 4px !important;
                    }
                    
                    /* Icon hover effects */
                    .attendance-icon:hover {
                        transform: scale(1.2);
                        transition: transform 0.2s ease;
                    }
                    
                    /* Table responsiveness */
                    @media (max-width: 768px) {
                        .fi-ta-table {
                            font-size: 12px;
                        }
                        
                        .sticky {
                            font-size: 11px;
                        }
                    }
                    
                    /* Custom scrollbar */
                    .fi-ta-table-container::-webkit-scrollbar {
                        height: 8px;
                    }
                    
                    .fi-ta-table-container::-webkit-scrollbar-track {
                        background: #f1f5f9;
                        border-radius: 4px;
                    }
                    
                    .fi-ta-table-container::-webkit-scrollbar-thumb {
                        background: #cbd5e1;
                        border-radius: 4px;
                    }
                    
                    .fi-ta-table-container::-webkit-scrollbar-thumb:hover {
                        background: #94a3b8;
                    }
                    
                    /* Tooltip improvements */
                    [title]:hover::after {
                        white-space: pre-line;
                    }
                </style>
            '
        ];
    }
}