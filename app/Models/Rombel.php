<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasFactory;

    protected $table = 'rombel';

    protected $fillable = [
        'tapel_id',
        'semester_id',
        'name',
        'jurusan_id',
        'kelas_id',
        'guru_id',
        'siswa_id',
    ];

    public function jabatan_guru()
    {
        return $this->hasOne(JabatanGuru::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function tapel()
    {
        return $this->belongsTo(Tapel::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function siswa_rombel()
    {
        return $this->hasMany(SiswaRombel::class,);
    }
}
