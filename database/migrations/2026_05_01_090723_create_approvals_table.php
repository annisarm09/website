<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('approval')) {
            Schema::create('approval', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('tipe');
                $table->unsignedBigInteger('referensi_id');
                $table->string('judul');
                $table->text('preview')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->unsignedBigInteger('dibuat_oleh')->nullable();
                $table->unsignedBigInteger('disetujui_oleh')->nullable();
                $table->timestamp('disetujui_at')->nullable();
                $table->text('catatan')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('approval');
    }
};