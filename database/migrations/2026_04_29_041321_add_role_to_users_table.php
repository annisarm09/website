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
        Schema::table('users', function (Blueprint $table) {
          // ✅ Cek dulu sebelum tambah, agar tidak error jika sudah ada
        if (!Schema::hasColumn('users', 'role')) {
            $table->enum('role', ['user', 'pimpinan', 'admin'])
                  ->default('user')
                  ->nullable(false);
        }
{


}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
