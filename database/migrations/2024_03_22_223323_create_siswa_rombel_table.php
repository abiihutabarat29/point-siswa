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
        Schema::create('siswa_rombel', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tapel_id');
            $table->unsignedBigInteger('semester_id');
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('rombel_id');
            $table->unsignedBigInteger('kelas_id');
            $table->timestamps();

            $table->foreign('tapel_id')->references('id')->on('tapel')->onDelete('cascade');
            $table->foreign('semester_id')->references('id')->on('semester')->onDelete('cascade');
            $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');
            $table->foreign('rombel_id')->references('id')->on('rombel')->onDelete('cascade');
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_rombel');
    }
};
