<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fix data lama: yang sudah dibalas (dibalas_at terisi) tapi status masih 'Baru'
        DB::table('pesan')
            ->whereNotNull('dibalas_at')
            ->where('status', 'Baru')
            ->update([
                'status'       => 'Sudah Dibalas',
                'sudah_dibaca' => true,
            ]);

        // Fix data yang sudah_dibaca = true tapi status masih 'Baru' (tanpa dibalas_at)
        DB::table('pesan')
            ->where('sudah_dibaca', true)
            ->whereNull('dibalas_at')
            ->where('status', 'Baru')
            ->update(['status' => 'Dibaca']);
    }

    public function down(): void
    {
        // Tidak perlu rollback data
    }
};