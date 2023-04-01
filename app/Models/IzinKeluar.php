<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IzinKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_nik',
        'tanggal',
        'jam_keluar',
        'jam_kembali',
        'is_approved',
        'approval_id',
    ];
}