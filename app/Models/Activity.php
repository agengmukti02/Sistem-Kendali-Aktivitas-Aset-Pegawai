<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    //fillable tabel activity
    protected $fillable = [
        'employee_id',
        'jenis',
        'tanggal_awal',
        'tanggal_akhir',
        'nomor_surat',
        'tanggal_surat',
        'keperluan',
        'uraian',
        'status',
        'approved_by_id',
    ];

    protected $casts = [
        'tanggal_awal' => 'date',
        'tanggal_akhir' => 'date',
        'tanggal_surat' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($activity) {
            $exists = Activity::where('employee_id', $activity->employee_id)
                ->where('status', 'disetujui')
                ->where(function ($q) use ($activity) {
                    $q->whereBetween('tanggal_awal', [$activity->tanggal_awal, $activity->tanggal_akhir])
                    ->orWhereBetween('tanggal_akhir', [$activity->tanggal_awal, $activity->tanggal_akhir]);
                })
                ->exists();

            if ($exists) {
                throw new \Exception('Pegawai sudah memiliki aktivitas pada rentang tanggal tersebut!');
            }
        });
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by_id');
    }
}
