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
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('jurusan_prodi');
            $table->string('mengajar'); // Mata pelajaran
            $table->string('kelas');
            $table->timestamps();
        });        
    }

    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
