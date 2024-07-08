<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruMapel extends Model
{
    use HasFactory;

    protected $table = 'guru_mapel';

    protected $fillable = [
        'tapel_id',
        'semester_id',
        'mapel_id',
        'guru_id',
        'rombel_id'
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }
}
