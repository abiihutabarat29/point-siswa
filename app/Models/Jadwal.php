<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'tapel_id',
        'semester_id',
        'rombel_id',
        'durasi',
        'jam_mulai',
        'jlh_mapel',
        'jlh_istirahat',
    ];

    public function tapel()
    {
        return $this->belongsTo(Tapel::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }

    public function jadwal_istirahat()
    {
        return $this->hasMany(JadwalIstirahat::class);
    }

    public function jadwal_mapel()
    {
        return $this->hasMany(JadwalMapel::class);
    }
}
