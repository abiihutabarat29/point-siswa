<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelanggaranSiswa extends Model
{
    use HasFactory;

    protected $table = 'pelanggaran_siswa';

    protected $fillable = [
        'tapel_id',
        'semester_id',
        'rombel_id',
        'siswa_id',
        'pelanggaran_id',
        'user_id',
        'foto',
        'keterangan',
        'status',
    ];

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'rombel_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function pelanggaran()
    {
        return $this->belongsTo(Pelanggaran::class);
    }
}
