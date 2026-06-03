<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;

class PeminjamController extends Controller
{
    public function index()
    {
        // Mengambil semua data peminjam
        $peminjams = Peminjam::all();
        return view('peminjam.index', compact('peminjams'));
    }
}
