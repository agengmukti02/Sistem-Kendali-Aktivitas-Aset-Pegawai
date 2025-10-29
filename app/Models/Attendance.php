<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    // Status constants
    const STATUS_HADIR = 'hadir';
    const STATUS_SAKIT = 'sakit';
    const STATUS_IZIN = 'izin';
    const STATUS_CUTI = 'cuti';
    const STATUS_DINAS_DALAM = 'dinas_dalam';
    const STATUS_DINAS_LUAR = 'dinas_luar';
    const STATUS_ALPHA = 'alpha';
    const STATUS_LIBUR = 'libur';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_HADIR => 'Hadir',
            self::STATUS_SAKIT => 'Sakit',
            self::STATUS_IZIN => 'Izin',
            self::STATUS_CUTI => 'Cuti',
            self::STATUS_DINAS_DALAM => 'Dinas Dalam',
            self::STATUS_DINAS_LUAR => 'Dinas Luar',
            self::STATUS_ALPHA => 'Alpha',
            self::STATUS_LIBUR => 'Libur',
        ];
    }

    public static function getStatusCodes(): array
    {
        return [
            self::STATUS_HADIR => 'H',
            self::STATUS_SAKIT => 'S',
            self::STATUS_IZIN => 'I',
            self::STATUS_CUTI => 'C',
            self::STATUS_DINAS_DALAM => 'DD',
            self::STATUS_DINAS_LUAR => 'DL',
            self::STATUS_ALPHA => 'TK',
            self::STATUS_LIBUR => '0',
        ];
    }

    public static function getStatusColors(): array
    {
        return [
            self::STATUS_HADIR => 'success',
            self::STATUS_SAKIT => 'warning',
            self::STATUS_IZIN => 'info',
            self::STATUS_CUTI => 'primary',
            self::STATUS_DINAS_DALAM => 'secondary',
            self::STATUS_DINAS_LUAR => 'secondary',
            self::STATUS_ALPHA => 'danger',
            self::STATUS_LIBUR => 'gray',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // Helper methods
    public function getStatusCodeAttribute(): string
    {
        return self::getStatusCodes()[$this->status] ?? 'TK';
    }

    public function getStatusColorAttribute(): string
    {
        return self::getStatusColors()[$this->status] ?? 'gray';
    }

    public function getFormattedCheckInAttribute(): ?string
    {
        return $this->check_in ? $this->check_in->format('Hi') : null;
    }

    public function getFormattedCheckOutAttribute(): ?string
    {
        return $this->check_out ? $this->check_out->format('Hi') : null;
    }

    public function getDisplayTextAttribute(): string
    {
        if ($this->status === self::STATUS_LIBUR) {
            return '0';
        }

        if ($this->status === self::STATUS_ALPHA) {
            return 'TK';
        }

        $text = '';
        
        if ($this->check_in) {
            $text .= $this->formatted_check_in;
        }

        if ($this->check_out) {
            $text .= $this->check_out->format('Hi:s');
        } elseif ($this->check_in && $this->status === self::STATUS_HADIR) {
            $text .= ' TPP';
        }

        if (!$this->check_in && !$this->check_out) {
            $text = $this->status_code;
        }

        return $text;
    }

    public function getWorkingHoursAttribute(): ?string
    {
        if ($this->check_in && $this->check_out) {
            $diff = $this->check_out->diff($this->check_in);
            return $diff->format('%h:%I');
        }
        return null;
    }

    // Scope untuk mendapatkan data presensi bulanan
    public function scopeForMonth($query, int $year, int $month)
    {
        return $query->whereYear('date', $year)
                    ->whereMonth('date', $month);
    }

    public function scopeForEmployee($query, int $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }
}
