<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $table = 'approval';

    protected $fillable = [
        'tipe',
        'referensi_id',
        'judul',
        'preview',
        'status',
        'dibuat_oleh',
        'disetujui_oleh',
        'disetujui_at',
        'catatan',
    ];

    protected $casts = [
        'disetujui_at' => 'datetime',
    ];

    // Relasi ke user yang membuat (admin)
    public function pembuatUser()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    // Relasi ke user yang menyetujui (pimpinan)
    public function penyetujuUser()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
