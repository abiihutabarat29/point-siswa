<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaAbsensi extends Model
{
    use HasFactory;

    protected $table = 'siswa_absensi';

    protected $fillable = [
        'jadwal_mapel_id',
        'guru_id',
        'siswa_id',
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

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }
}
