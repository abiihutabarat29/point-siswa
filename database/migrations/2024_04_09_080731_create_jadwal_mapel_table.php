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
        Schema::create('jadwal_mapel', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_id');
            $table->unsignedBigInteger('hari_id');
            $table->unsignedBigInteger('guru_mapel_id')->nullable();
            $table->bigInteger('jam_ke');
            $table->string('start');
            $table->string('end');

            $table->timestamps();

            $table->foreign('jadwal_id')->references('id')->on('jadwal')->onDelete('cascade');
            $table->foreign('hari_id')->references('id')->on('hari')->onDelete('cascade');
            $table->foreign('guru_mapel_id')->references('id')->on('guru_mapel')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_mapel');
    }
};
