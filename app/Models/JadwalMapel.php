<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalMapel extends Model
{
    use HasFactory;

    protected $table = 'jadwal_mapel';

    protected $fillable = [
        'jadwal_id',
        'hari_id',
        'jam_ke',
        'guru_mapel_id',
        'start',
        'end'
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function hari()
    {
        return $this->belongsTo(Hari::class);
    }

    public function guru_mapel()
    {
        return $this->belongsTo(GuruMapel::class);
    }
}
