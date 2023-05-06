<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiAbsen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_nik',
        'tanggal',
        'jam',
        'direvisi',
        'alasan'
    ];
}