<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('peminjaman', function (Blueprint $table) {
        $table->id();

        // GUNAKAN foreignId AGAR TIPE DATANYA OTOMATIS COCOK DENGAN id
        $table->foreignId('peminjam_id')->constrained('peminjam')->onDelete('cascade');
        $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');

        $table->date('tanggal_pinjam');
        $table->date('tanggal_kembali')->nullable();
        $table->integer('jumlah_pinjam');
        $table->enum('status_peminjaman', ['Dipinjam', 'Dikembalikan', 'Terlambat'])->default('Dipinjam');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
