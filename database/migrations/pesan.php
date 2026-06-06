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
        Schema::create('pesan', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->string('kontak');   // email atau WhatsApp
    $table->text('pesan');
    $table->boolean('sudah_dibaca')->default(false);
     $table->timestap('dibalas_at')->nullable();        
    $table->date('tanggal')->nullable();
    $table->enum('status', ['Baru', 'Dibaca', 'Sudah Dibalas'])->default('Baru');
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan');
    }
};
