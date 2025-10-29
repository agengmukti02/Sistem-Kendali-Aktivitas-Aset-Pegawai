<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasSimpleRoles;

class Employee extends Model
{
    use HasFactory;
    use HasSimpleRoles;

    protected $fillable = [
        'name',
        'nip',
        'golongan',
        'jabatan',
        'status',
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
    
    public function workSchedules()
    {
        return $this->hasMany(WorkSchedule::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function alasanDinas()
    {
        return $this->belongsTo(Reason::class);
    }
}
