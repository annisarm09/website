<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom `status` ke tabel galeri
     * Nilai: 'pending' | 'published' | 'rejected'
     * Default 'pending' agar semua upload baru wajib disetujui pimpinan.
     *
     * Jalankan dengan:
     *   php artisan migrate
     */
    public function up(): void
    {
        Schema::table('galeri', function (Blueprint $table) {
            $table->enum('status', ['pending', 'published', 'rejected'])
                  ->default('pending')
                  ->after('timestamp')
                  ->comment('pending = menunggu, published = tampil, rejected = ditolak');
        });

        // Foto lama yang sudah ada di DB langsung dianggap published
        // agar tidak hilang dari halaman publik setelah migrasi
        DB::table('galeri')->update(['status' => 'published']);
    }

    public function down(): void
    {
        Schema::table('galeri', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
