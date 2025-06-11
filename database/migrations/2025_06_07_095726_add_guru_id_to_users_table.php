<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('guru_id')->nullable()->after('id');
        $table->foreign('guru_id')->references('id')->on('gurus')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['guru_id']);
        $table->dropColumn('guru_id');
    });
}
};
