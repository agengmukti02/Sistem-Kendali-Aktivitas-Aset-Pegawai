<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class EmployeeSubmission extends Page
{
    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('jenis')
                ->options(['dinas' => 'Dinas', 'dinas_luar' => 'Dinas Luar', 'cuti' => 'Cuti', 'diklat' => 'Diklat', 'lainnya' => 'Lainnya'])
                ->required(),
            Forms\Components\DatePicker::make('tanggal_awal')->required(),
            Forms\Components\DatePicker::make('tanggal_akhir')->required(),
            Forms\Components\Textarea::make('keperluan')->required(),
        ]);
    }

    public function submit()
    {
        Activity::create([
            'employee_id' => auth()->user()->employee->id,
            'jenis' => $this->form->getState()['jenis'],
            'tanggal_awal' => $this->form->getState()['tanggal_awal'],
            'tanggal_akhir' => $this->form->getState()['tanggal_akhir'],
            'keperluan' => $this->form->getState()['keperluan'],
            'status' => 'pending',
        ]);

        $this->notify('success', 'Pengajuan aktivitas berhasil dibuat!');
    }
}
