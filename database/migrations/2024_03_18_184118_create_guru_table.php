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
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->nullable();;
            $table->string('nik')->nullable();
            $table->string('name')->nullable();;
            $table->string('gender')->nullable();;
            $table->string('status')->nullable();
            $table->string('tlp')->nullable();
            $table->string('kode')->nullable();
            $table->string('tmp_lahir')->nullable();
            $table->string('tgl_lahir')->nullable();
            $table->string('address')->nullable();
            $table->string('agama')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
