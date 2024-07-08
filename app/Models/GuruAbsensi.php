<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruAbsensi extends Model
{
    use HasFactory;

    protected $table = 'guru_absensi';

    protected $fillable = [
        'jadwal_id',
        'guru_id',
        'tgl_absen',
        'jam_absen',
        'status'
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function guru()
    {
        return $this->hasMany(Guru::class);
    }
}
