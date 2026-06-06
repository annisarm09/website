<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Galeri extends Model
{
    protected $table = 'galeri';

    protected $fillable = [
        'judul',
        'src',
        'kategori',
        'timestamp',
        'status',       // 'pending' | 'published' | 'rejected'
    ];

    // ── Accessor: URL gambar ─────────────────────────────────────
    public function getSrcUrlAttribute(): string
    {
        if ($this->src && Storage::disk('public')->exists($this->src)) {
            return asset('storage/' . $this->src);
        }
        return asset('images/default.jpg');
    }

    // ── Scope helpers ────────────────────────────────────────────

    /** Hanya foto yang sudah disetujui pimpinan */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /** Foto yang sedang menunggu persetujuan */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /** Foto yang ditolak */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
