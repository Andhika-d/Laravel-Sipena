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
        Schema::table('absensi_guru', function (Blueprint $table) {
            $table->enum('status_kehadiran', ['hadir', 'izin', 'sakit', 'alfa'])->default('hadir');
            $table->text('deskripsi')->nullable();
            $table->string('file_pendukung')->nullable();
        });
    }

    public function down()
    {
        Schema::table('absensi_guru', function (Blueprint $table) {
            $table->dropColumn(['status_kehadiran', 'deskripsi', 'file_pendukung']);
        });
    }
};
