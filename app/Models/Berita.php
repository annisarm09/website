<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Berita extends Model
{
    protected $table = 'berita';

    protected $fillable = [
        'judul_berita',
        'isi_berita',
        'foto',
        'slug',
        'kategori',   // ✅ wajib ada di fillable
        'status',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($berita) {
            if (empty($berita->slug) && !empty($berita->judul_berita)) {
                $slug = Str::slug($berita->judul_berita);
                $base = $slug;
                $i    = 1;
                while (static::whereSlug($slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $berita->slug = $slug;
            }
        });
    }
}
