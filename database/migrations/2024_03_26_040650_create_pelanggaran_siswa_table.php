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
        Schema::create('pelanggaran_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rombel_id');
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('pelanggaran_id');
            $table->unsignedBigInteger('user_id');
            $table->string('foto')->nullable();
            $table->longText('keterangan')->nullable();
            $table->longText('alasan')->nullable();
            $table->string('status')->default(0);
            $table->timestamps();

            $table->foreign('rombel_id')->references('id')->on('rombel');
            $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');
            $table->foreign('pelanggaran_id')->references('id')->on('pelanggaran');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggaran_siswa');
    }
};
