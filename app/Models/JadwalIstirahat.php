<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalIstirahat extends Model
{
    use HasFactory;

    protected $table = 'jadwal_istirahat';

    protected $fillable = [
        'jadwal_id',
        'jam_ke',
        'durasi',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}
