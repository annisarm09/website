<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('berandas')) {
            Schema::create('berandas', function (Blueprint $table) {
                $table->id();
                $table->string('judul_utama')->nullable();
                $table->string('sub_judul')->nullable();
                $table->text('deskripsi')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tentangs')) {
            Schema::create('tentangs', function (Blueprint $table) {
                $table->id();
                $table->string('tipe');
                $table->string('judul')->nullable();
                $table->text('konten')->nullable();
                $table->string('icon')->nullable();
                $table->integer('urutan')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('berandas');
        Schema::dropIfExists('tentangs');
    }
};
