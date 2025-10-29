<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'activity_id',
        'path',
        'jenis_dokumen',
        'filename',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
