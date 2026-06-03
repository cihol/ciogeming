<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use Illuminate\Http\Request;

class PeminjamController extends Controller
{
    /**
     * Menampilkan daftar peminjam (READ ONLY - dari Seeder)
     */
    public function index()
    {
        $peminjams = Peminjam::orderBy('nama_peminjam')->paginate(10);
        return view('peminjams.index', compact('peminjams'));
    }

    /**
     * Menampilkan detail peminjam
     */
    public function show(int $id)
    {
        $peminjam = Peminjam::with('peminjamans.barang')->findOrFail($id);
        return view('peminjams.show', compact('peminjam'));
    }
}
