<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaRombel extends Model
{
    use HasFactory;

    protected $table = 'siswa_rombel';

    protected $fillable = [
        'tapel_id',
        'semester_id',
        'siswa_id',
        'rombel_id',
        'kelas_id',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }
}
