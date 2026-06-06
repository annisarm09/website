<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::table('galeri', function (Blueprint $table) {

            // Tambah kolom 'judul' jika belum ada
            if (!Schema::hasColumn('galeri', 'judul')) {
                $table->string('judul')->after('id');
            }

            // Tambah kolom 'src' jika belum ada
            if (!Schema::hasColumn('galeri', 'src')) {
                $table->string('src')->after('judul');
            }

            // Tambah kolom 'kategori' jika belum ada
            if (!Schema::hasColumn('galeri', 'kategori')) {
                $table->enum('kategori', ['pengajian', 'tahfidz', 'sport', 'wisuda', 'lainnya'])
                      ->default('lainnya')
                      ->after('src');
            }

            // Tambah kolom 'timestamp' jika belum ada
            if (!Schema::hasColumn('galeri', 'timestamp')) {
                $table->text('timestamp')->nullable()->after('kategori');
            }
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            //
        });
    }
};
