<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'tanggal',
    ];

    public function details()
    {
        return $this->hasMany(PresensiDetail::class, 'id_presensi', 'id_presensi');
    }
}
