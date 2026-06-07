<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('berita')) {
            Schema::create('berita', function (Blueprint $table) {
                $table->id();
                $table->string('judul_berita');
                $table->string('slug')->unique()->nullable();
                $table->text('isi_berita');
                $table->string('foto')->nullable();
                $table->string('kategori')->default('kegiatan');
                $table->date('tanggal')->nullable();
                $table->enum('status', ['pending', 'published', 'rejected', 'draft'])->default('pending');
                $table->timestamp('tgl_keputusan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
