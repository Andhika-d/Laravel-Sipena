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
    Schema::create('guru_mapel', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('guru_id');
        $table->unsignedBigInteger('mapel_id');
        $table->timestamps();

        // Foreign Key Constraints (kalau mau)
        $table->foreign('guru_id')->references('id')->on('gurus')->onDelete('cascade');
        $table->foreign('mapel_id')->references('id')->on('mapel')->onDelete('cascade');
        
        // Biar gak dobel insert relasi yang sama
        $table->unique(['guru_id', 'mapel_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_mapel');
    }
};
