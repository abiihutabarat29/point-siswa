<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JabatanGuru extends Model
{
    use HasFactory;

    protected $table = 'jabatan_guru';

    protected $fillable = [
        'tapel_id',
        'semester_id',
        'jabatan_id',
        'guru_id',
        'rombel_id'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }
}
