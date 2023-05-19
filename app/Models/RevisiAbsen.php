<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiAbsen extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'nik');
    }

    protected $fillable = [
        'user_nik',
        'tanggal',
        'jam',
        'yang_direvisi',
        'bukti_path',
        'alasan'
    ];
}