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
                $table->string('src')->nullable();
                $table->enum('kategori', ['pengajian', 'tahfidz', 'sport', 'wisuda', 'lainnya'])->default('lainnya');
                $table->text('timestamp')->nullable();
                $table->string('status')->default('pending');
                $table->timestamp('tgl_keputusan')->nullable();
                $table->timestamps();
            });
        } else {
            if (!Schema::hasColumn('galeri', 'judul')) {
                Schema::table('galeri', function (Blueprint $table) {
                    $table->string('judul')->nullable();
                });
            }
            if (!Schema::hasColumn('galeri', 'src')) {
                Schema::table('galeri', function (Blueprint $table) {
                    $table->string('src')->nullable();
                });
            }
            if (!Schema::hasColumn('galeri', 'kategori')) {
                Schema::table('galeri', function (Blueprint $table) {
                    $table->enum('kategori', ['pengajian', 'tahfidz', 'sport', 'wisuda', 'lainnya'])->default('lainnya');
                });
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri');
    }
};