<?php

namespace Database\Seeders;

use App\Models\Peminjam;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
public function run()
{
    User::create([
        'name' => 'Format Admin',
        'email' => 'admin@tefa.com',
        'password' => Hash::make('12345'),
        'role' => 'Admin'
    ]);

    User::create([
        'name' => 'Format Petugas',
        'email' => 'petugas@tefa.com',
        'password' => Hash::make('99999'),
        'role' => 'Petugas'
    ]);

    Peminjam::insert([
        ['nama_peminjam' => 'Ahmad Fauzan', 'kelas' => 'XI', 'jurusan' => 'PPLG 1', 'no_hp' => '081234567890'],
        ['nama_peminjam' => 'Rizky Pratama', 'kelas' => 'XI', 'jurusan' => 'PPLG 2', 'no_hp' => '081234567891'],
        ['nama_peminjam' => 'Dinda Putri', 'kelas' => 'XI', 'jurusan' => 'PPLG 1', 'no_hp' => '081234567892'],
    ]);

    Barang::insert([
        ['nama_barang' => 'Laptop Asus', 'kategori_barang' => 'Laptop', 'stok' => 10, 'kondisi_barang' => 'Baik'],
        ['nama_barang' => 'Mouse Logitech', 'kategori_barang' => 'Aksesoris', 'stok' => 15, 'kondisi_barang' => 'Baik'],
        ['nama_barang' => 'Keyboard Mechanical', 'kategori_barang' => 'Aksesoris', 'stok' => 8, 'kondisi_barang' => 'Baik'],
    ]);
}
}
