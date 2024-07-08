<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tendik extends Model
{
    use HasFactory;

    protected $table = 'teendik';

    protected $fillable = [
        'id_card',
        'name',
        'gender',
        'tmp_lahir',
        'tgl_lahir',
        'address',
        'tlp',
        'photo',
    ];
}
