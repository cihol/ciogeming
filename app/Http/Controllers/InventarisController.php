<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Peminjam;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InventarisController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function dashboard() {
        $totalBarang = Barang::count();
        $totalPeminjaman = Peminjaman::where('status_peminjaman', 'Dipinjam')->count();
        $totalPeminjam = Peminjam::count();

        // Notifikasi Stok Menipis (Stok kurang dari 3)
        $stokMenipis = Barang::where('stok', '<', 3)->get();

        return view('dashboard', compact('totalBarang', 'totalPeminjaman', 'totalPeminjam', 'stokMenipis'));
    }

    public function indexBarang() {
        $barang = Barang::all();
        return view('barang.index', compact('barang'));
    }

    public function storeBarang(Request $request) {
        // Proteksi Role: Hanya Admin yang bisa Tambah Barang
        if (Auth::user()->role !== 'Admin') { return back()->with('error', 'Akses ditolak! Hanya Admin yang boleh menambah barang.'); }

        $request->validate([
            'nama_barang' => 'required|string',
            'kategori_barang' => 'required|string',
            'stok' => 'required|integer|min:0',
            'kondisi_barang' => 'required|string',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        // Proses Upload Foto
        if ($request->hasFile('foto_barang')) {
            $file = $request->file('foto_barang');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/barang'), $nama_file);
            $data['foto_barang'] = $nama_file;
        }

        Barang::create($data);
        return redirect()->back()->with('success', 'Data barang berhasil ditambahkan!');
    }

    public function updateBarang(Request $request, $id) {
        if (Auth::user()->role !== 'Admin') { return back()->with('error', 'Akses ditolak! Hanya Admin yang boleh mengubah barang.'); }

        $barang = Barang::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('foto_barang')) {
            // Hapus foto lama jika ada
            if ($barang->foto_barang && file_exists(public_path('uploads/barang/' . $barang->foto_barang))) {
                unlink(public_path('uploads/barang/' . $barang->foto_barang));
            }
            $file = $request->file('foto_barang');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/barang'), $nama_file);
            $data['foto_barang'] = $nama_file;
        }

        $barang->update($data);
        return redirect()->back()->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroyBarang($id) {
        if (Auth::user()->role !== 'Admin') { return back()->with('error', 'Akses ditolak! Hanya Admin yang boleh menghapus barang.'); }

        $barang = Barang::findOrFail($id);
        if ($barang->foto_barang && file_exists(public_path('uploads/barang/' . $barang->foto_barang))) {
            unlink(public_path('uploads/barang/' . $barang->foto_barang));
        }
        $barang->delete();
        return redirect()->back()->with('success', 'Data barang berhasil dihapus!');
    }

    public function indexPeminjaman() {
        $peminjaman = Peminjaman::with(['peminjam', 'barang'])->get();
        $peminjam = Peminjam::all();
        $barang = Barang::where('stok', '>', 0)->get();
        return view('peminjaman.index', compact('peminjaman', 'peminjam', 'barang'));
    }

    public function storePeminjaman(Request $request) {
        $request->validate([
            'peminjam_id' => 'required',
            'barang_id' => 'required',
            'tanggal_pinjam' => 'required|date',
            'jumlah_pinjam' => 'required|integer|min:1',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($request->jumlah_pinjam > $barang->stok) {
            return redirect()->back()->with('error', 'Jumlah pinjam melebihi stok yang tersedia!');
        }

        Peminjaman::create([
            'peminjam_id' => $request->peminjam_id,
            'barang_id' => $request->barang_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'jumlah_pinjam' => $request->jumlah_pinjam,
            'status_peminjaman' => 'Dipinjam'
        ]);

        $barang->decrement('stok', $request->jumlah_pinjam);
        return redirect()->back()->with('success', 'Peminjaman berhasil dicatat!');
    }

    public function updatePeminjaman(Request $request, $id) {
        $peminjaman = Peminjaman::findOrFail($id);
        $status_baru = $request->status_peminjaman;

        if ($status_baru == 'Dikembalikan' && $peminjaman->status_peminjaman != 'Dikembalikan') {
            $barang = Barang::findOrFail($peminjaman->barang_id);
            $barang->increment('stok', $peminjaman->jumlah_pinjam);
        }

        $peminjaman->update([
            'status_peminjaman' => $status_baru,
            'tanggal_kembali' => $status_baru == 'Dikembalikan' ? Carbon::now() : $peminjaman->tanggal_kembali
        ]);

        return redirect()->back()->with('success', 'Status peminjaman diperbarui!');
    }

    public function destroyPeminjaman($id) {
        $peminjaman = Peminjaman::findOrFail($id);
        if ($peminjaman->status_peminjaman != 'Dikembalikan') {
            $barang = Barang::findOrFail($peminjaman->barang_id);
            $barang->increment('stok', $peminjaman->jumlah_pinjam);
        }
        $peminjaman->delete();
        return redirect()->back()->with('success', 'Data peminjaman dihapus!');
    }

    public function indexPeminjam() {
        $peminjam = Peminjam::all();
        return view('peminjam.index', compact('peminjam'));
    }
}
