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
        Schema::table('gurus', function (Blueprint $table) {
            $table->unsignedBigInteger('kelas_id')->nullable()->after('id');

            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            //
        });
    }
};
