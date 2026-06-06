<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tentang extends Model
{
   protected $table = 'tentang';

    protected $fillable = [
        'deskripsi',
        'tipe',  // sejarah | visi | misi
    ];

}
