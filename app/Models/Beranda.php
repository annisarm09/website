<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beranda extends Model
{
    // Nama tabel mengikuti migration: 'dashboard'
    protected $table = 'dashboard';

    protected $fillable = [
        'judul_utama',
        'sub_judul',
        'deskripsi',
    ];
}
