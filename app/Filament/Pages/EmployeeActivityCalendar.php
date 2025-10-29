<?php

namespace App\Filament\Pages;

use App\Models\Activity;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class EmployeeActivityCalendar extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-calendar';
    protected static string | UnitEnum | null $navigationGroup = 'Manajemen Aktivitas';
    protected string $view = 'filament.pages.employee-activity-calendar';

    public $events = [];

    public function mount()
    {
        $this->events = Activity::with('employee')
            ->get()
            ->map(function ($a) {
                return [
                    'title' => $a->employee->name . ' - ' . ucfirst($a->jenis),
                    'start' => $a->tanggal_awal,
                    'end'   => $a->tanggal_akhir,
                    'color' => match ($a->jenis) {
                        'dinas'         => '#3b82f6', // biru
                        'cuti'          => '#22c55e', // hijau
                        'diklat'        => '#facc15', // kuning
                        'tugas_belajar' => '#a855f7', // ungu
                        'dinas_luar'    => '#8b5cf6', // ungu tua
                        default         => '#ef4444', // merah
                    },
                ];
            })->toArray();
    }
}
