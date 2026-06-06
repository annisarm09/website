<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('berandas', function (Blueprint $table) {
            // Slide 1
            $table->string('slide1_label')->default('Selamat datang di')->after('deskripsi');
            $table->string('slide1_judul')->default('ASH-SHIDDIQIN')->after('slide1_label');
            $table->string('slide1_sub')->default('Pondok Pesantren Modern Palembang')->after('slide1_judul');
            $table->string('slide1_btn1')->default('Pelajari Lebih Lanjut →')->after('slide1_sub');
            $table->string('slide1_btn2')->default('Informasi Pendaftaran →')->after('slide1_btn1');

            // Slide 2
            $table->string('slide2_label')->default('Generasi Terbaik')->after('slide1_btn2');
            $table->string('slide2_judul')->default('KOKOH IMAN & PRODUKTIF')->after('slide2_label');
            $table->string('slide2_sub')->default('Manhaj Islam Wasathiyah')->after('slide2_judul');
            $table->string('slide2_btn1')->default('Berita Terbaru →')->after('slide2_sub');
            $table->string('slide2_btn2')->default('Daftar Sekarang →')->after('slide2_btn1');

            // Slide 3
            $table->string('slide3_label')->default('Program Unggulan')->after('slide2_btn2');
            $table->string('slide3_judul')->default('TAHFIDZ AL-QURAN')->after('slide3_label');
            $table->string('slide3_sub')->default('Target 30 Juz Bersama Ustadz Terbaik')->after('slide3_judul');
            $table->string('slide3_btn1')->default('Lihat Program →')->after('slide3_sub');
            $table->string('slide3_btn2')->default('Lihat Galeri →')->after('slide3_btn1');
        });
    }

    public function down(): void
    {
        Schema::table('berandas', function (Blueprint $table) {
            $table->dropColumn([
                'slide1_label','slide1_judul','slide1_sub','slide1_btn1','slide1_btn2',
                'slide2_label','slide2_judul','slide2_sub','slide2_btn1','slide2_btn2',
                'slide3_label','slide3_judul','slide3_sub','slide3_btn1','slide3_btn2',
            ]);
        });
    }
};
