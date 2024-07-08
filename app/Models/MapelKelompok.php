<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapelKelompok extends Model
{
    use HasFactory;

    protected $table = 'mapel_kelompok';

    protected $fillable = [
        'name',
        'kode',
        'kategori',
        'parent'
    ];
}
