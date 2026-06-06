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
        Schema::create('berita', function (Blueprint $table) {
    $table->id();
    $table->string('judul_berita')->nullable();
    $table->string('slug')->nullable()->unique();
    $table->text('isi_berita')->nullable();
    $table->enum('status', ['draft', 'pending', 'published', 'rejected'])->default('draft');
    $table->string('foto')->nullable();
    $table->date('tanggal')->nullable();
    $table->enum('kategori', ['kegiatan', 'pengumuman', 'prestasi'])->default('kegiatan');
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
