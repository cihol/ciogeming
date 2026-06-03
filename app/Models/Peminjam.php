<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjam extends Model
{
    use HasFactory;

    // Beritahu Laravel nama tabel yang benar (tanpa 's')
    protected $table = 'peminjam';

   protected $fillable = [
    'nama_peminjam',
    'kelas',
    'jurusan',
    'no_hp',
    'foto', // Tambahkan ini
    ];
}
