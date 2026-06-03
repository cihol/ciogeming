<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use App\Models\Barang;
use Illuminate\Http\Request;

class PeminjamController extends Controller
{
    // Menampilkan daftar peminjam
    public function index()
    {
        $peminjams = Peminjam::latest()->get();
        $barang    = Barang::orderBy('nama_barang')->get(); // ✅ FIX: kirim $barang ke view

        return view('peminjam.index', compact('peminjams', 'barang'));
    }

    // Form tambah peminjam (jika pakai halaman terpisah)
    public function create()
    {
        $barang = Barang::orderBy('nama_barang')->get();
        return view('peminjam.create', compact('barang'));
    }

    // Simpan peminjam baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'kelas'         => 'required|string|max:100',
            'no_hp'         => 'nullable|string|max:20',
        ], [
            'nama_peminjam.required' => 'Nama peminjam wajib diisi.',
            'kelas.required'         => 'Kelas wajib diisi.',
        ]);

        // Gunakan only() agar tidak ada field liar masuk ke DB
        Peminjam::create($request->only([
            'nama_peminjam',
            'kelas',
            'no_hp',
        ]));

        return redirect()->route('peminjam.index')
            ->with('success', 'Data peminjam berhasil ditambahkan!');
    }

    // Update data peminjam
    public function update(Request $request, $id)
    {
        $peminjam = Peminjam::findOrFail($id);

        $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'kelas'         => 'required|string|max:100',
            'no_hp'         => 'nullable|string|max:20',
        ], [
            'nama_peminjam.required' => 'Nama peminjam wajib diisi.',
            'kelas.required'         => 'Kelas wajib diisi.',
        ]);

        $peminjam->update($request->only([
            'nama_peminjam',
            'kelas',
            'no_hp',
        ]));

        return redirect()->route('peminjam.index')
            ->with('success', 'Data peminjam berhasil diperbarui!');
    }

    // Hapus peminjam
    public function destroy($id)
    {
        Peminjam::findOrFail($id)->delete();

        return redirect()->route('peminjam.index')
            ->with('success', 'Data peminjam berhasil dihapus!');
    }
}
