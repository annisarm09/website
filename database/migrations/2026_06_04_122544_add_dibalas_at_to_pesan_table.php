<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pesan')) return;

        DB::table('pesan')
            ->whereNotNull('dibalas_at')
            ->where('status', 'Baru')
            ->update([
                'status'      => 'Sudah Dibalas',
                'sudah_dibaca' => true,
            ]);

        DB::table('pesan')
            ->where('sudah_dibaca', true)
            ->whereNull('dibalas_at')
            ->where('status', 'Baru')
            ->update(['status' => 'Dibaca']);
    }

    public function down(): void {}
};