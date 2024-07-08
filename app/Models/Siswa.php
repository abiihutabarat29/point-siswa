<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nisn',
        'name',
        'gender',
        'tmp_lahir',
        'tgl_lahir',
        'foto',
        'pendaftaran',
        'tgl_masuk',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function siswa_rombel()
    {
        return $this->hasMany(SiswaRombel::class);
    }
}
