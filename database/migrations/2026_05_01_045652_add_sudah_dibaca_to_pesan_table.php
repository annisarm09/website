<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pesan') && !Schema::hasColumn('pesan', 'sudah_dibaca')) {
            Schema::table('pesan', function (Blueprint $table) {
                $table->boolean('sudah_dibaca')->default(false);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pesan') && Schema::hasColumn('pesan', 'sudah_dibaca')) {
            Schema::table('pesan', function (Blueprint $table) {
                $table->dropColumn('sudah_dibaca');
            });
        }
    }
};