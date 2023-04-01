<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_presensi',
        'jenis',
        'jam',
        'longitude',
        'latitude',
        'img_path',
    ];
}
