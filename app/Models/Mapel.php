<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $table = 'mapel';

    protected $fillable = [
        'name',
        'kode',
        'kelompok_id',
        'status'
    ];

    public function kelompok()
    {
        return $this->belongsTo(MapelKelompok::class, 'kelompok_id');
    }

    public function guru_mapel()
    {
        return $this->hasMany(GuruMapel::class);
    }
}
