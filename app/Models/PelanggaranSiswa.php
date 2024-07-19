<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelanggaranSiswa extends Model
{
    use HasFactory;

    protected $table = 'pelanggaran_siswa';

    protected $fillable = [
        'siswa_id',
        'pelanggaran_id',
        'user_id',
        'keterangan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function pelanggaran()
    {
        return $this->belongsTo(Pelanggaran::class);
    }
}
