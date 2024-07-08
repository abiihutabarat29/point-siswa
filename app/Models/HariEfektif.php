<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HariEfektif extends Model
{
    use HasFactory;

    protected $table = 'hari_efektif';

    protected $fillable = [
        'tapel_id',
        'semester_id',
        'jumlah'
    ];
}
