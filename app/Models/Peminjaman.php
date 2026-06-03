<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $guarded = ['id'];

    public function peminjam()
    {
        return $this->belongsTo(Peminjam::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
