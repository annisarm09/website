<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('galeri') && !Schema::hasColumn('galeri', 'status')) {
            Schema::table('galeri', function (Blueprint $table) {
                $table->enum('status', ['pending', 'published', 'rejected'])
                      ->default('pending')
                      ->comment('pending = menunggu, published = tampil, rejected = ditolak');
            });
            DB::table('galeri')->update(['status' => 'published']);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('galeri') && Schema::hasColumn('galeri', 'status')) {
            Schema::table('galeri', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};