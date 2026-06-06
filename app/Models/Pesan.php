<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pesan extends Model
{
    protected $table = 'pesan';

    protected $fillable = [
        'nama',
        'kontak',
        'pesan',
        'sudah_dibaca',
        'tanggal',
        'status',
        'sudah_dibaca',
        'dibalas_at',     
    ];

    protected $casts = [
        'sudah_dibaca' => 'boolean',
        'dibalas_at'   => 'datetime', 
    ];


}
