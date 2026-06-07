<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pesan')) {
            Schema::create('pesan', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('kontak');
                $table->text('pesan');
                $table->boolean('sudah_dibaca')->default(false);
                $table->timestamp('dibalas_at')->nullable();
                $table->date('tanggal')->nullable();
                $table->enum('status', ['Baru', 'Dibaca', 'Sudah Dibalas'])->default('Baru');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pesan');
    }
};
