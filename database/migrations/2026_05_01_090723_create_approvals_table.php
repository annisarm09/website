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
        Schema::create('approval', function (Blueprint $table) {
            $table->id();
            $table->string('tipe');          // 'berita' | 'galeri' | 'beranda'
            $table->unsignedBigInteger('referensi_id'); // id dari tabel berita/galeri
            $table->string('judul');         // judul konten untuk preview
            $table->text('preview')->nullable(); // ringkasan konten
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('dibuat_oleh')->nullable(); // user_id admin
            $table->unsignedBigInteger('disetujui_oleh')->nullable(); // user_id pimpinan
            $table->timestamp('disetujui_at')->nullable();
            $table->text('catatan')->nullable(); // catatan penolakan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
