<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ketidakhadiran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'tanggal',
        'tanggal_selesai',
        'alasan',
        'potong_cuti',
        'jenis_cuti'
    ];
}
