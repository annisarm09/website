<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('galeri')) {
            Schema::create('galeri', function (Blueprint $table) {
                $table->id();
                $table->string('judul')->nullable();
                $table->string('src');
                $table->string('kategori')->nullable();
                $table->string('kegiatan')->nullable();
                $table->enum('status', ['pending', 'published', 'rejected'])->default('pending');
                $table->timestamp('tgl_keputusan')->nullable();
                $table->timestamp('timestamp')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri');
    }
};
