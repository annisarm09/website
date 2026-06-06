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
        Schema::create('tentang', function (Blueprint $table) {
    $table->id();
    $table->text('deskripsi');
    $table->string('tipe', 50); // sejarah | visi | misi | prog1-prog4 | keunggulan_1-keunggulan_1
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tentang');
    }
};
