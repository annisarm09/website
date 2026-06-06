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
       Schema::table('pesan', function (Blueprint $table) {
        // Menambahkan kolom boolean dengan nilai default false (0)
        $table->boolean('sudah_dibaca')->default(false)->after('isi_pesan');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesan', function (Blueprint $table) {
            //
        });
    }
};
