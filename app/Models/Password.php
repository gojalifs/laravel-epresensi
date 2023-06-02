<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Password extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'pass',
    ];

    /**
     * Get the user associated with the password.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}