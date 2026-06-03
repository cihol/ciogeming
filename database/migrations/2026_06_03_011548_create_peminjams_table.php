<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('peminjam', function (Blueprint $table) {
        $table->id();
        $table->string('nama_peminjam');
        $table->string('kelas');
        $table->string('jurusan');
        $table->string('no_hp')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjams');
    }
};
