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
        Schema::create('guru_absensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_mapel_id')->nullable();
            $table->unsignedBigInteger('guru_id')->nullable();
            $table->date('tgl_absen')->nullable();
            $table->string('jam_absen')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('jadwal_mapel_id')->references('id')->on('jadwal_mapel')->onDelete('cascade');
            $table->foreign('guru_id')->references('id')->on('guru')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_absensi');
    }
};
