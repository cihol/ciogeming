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
    Schema::table('users', function (Blueprint $table) {
        $table->string('role')->default('Petugas'); // Admin atau Petugas
    });

    Schema::table('barang', function (Blueprint $table) {
        $table->string('foto_barang')->nullable()->after('kondisi_barang');
    });
}

public function down()
{
    Schema::table('users', function ($table) { $table->dropColumn('role'); });
    Schema::table('barang', function ($table) { $table->dropColumn('foto_barang'); });
}
};
