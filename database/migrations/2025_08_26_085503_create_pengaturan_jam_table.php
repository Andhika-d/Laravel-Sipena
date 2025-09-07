<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pengaturan_jam', function (Blueprint $table) {
            $table->id();
            $table->time('jam_masuk_mulai')->default('06:00:00');
            $table->time('jam_masuk_selesai')->default('07:30:00');
            $table->time('jam_telat')->default('08:00:00');
            $table->time('jam_pulang')->default('14:00:00');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_jam');
    }
};
