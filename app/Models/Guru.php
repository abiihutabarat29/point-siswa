<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'nip',
        'nik',
        'name',
        'gender',
        'tlp',
        'kode',
        'tmp_lahir',
        'tgl_lahir',
        'address',
        'agama',
        'photo',
        'status'
    ];

    public function jabatan_guru()
    {
        return $this->hasOne(JabatanGuru::class);
    }

    public function rombel()
    {
        return $this->hasOne(Rombel::class, 'guru_id');
    }

    public function guru_mapel()
    {
        return $this->hasMany(GuruMapel::class, 'guru_id');
    }
}
