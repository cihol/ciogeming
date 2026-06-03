<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BarangController extends Controller
{
    /**
     * Menampilkan daftar semua barang.
     */
    public function index()
    {
        $barang = Barang::latest()->get();
        return view('barang.index', compact('barang'));
    }

    /**
     * Menampilkan detail satu barang (opsional, untuk route resource lengkap).
     */
    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.show', compact('barang'));
    }

    /**
     * Menyimpan barang baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nama_barang'     => 'required|string|max:255',
            'kategori_barang' => 'required|string|max:255',
            'stok'            => 'required|integer|min:0',
            'kondisi_barang'  => 'required|in:Baik,Rusak',
            'foto_barang'     => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_barang.required'     => 'Nama barang wajib diisi.',
            'kategori_barang.required' => 'Kategori barang wajib diisi.',
            'stok.required'            => 'Stok wajib diisi.',
            'stok.integer'             => 'Stok harus berupa angka.',
            'stok.min'                 => 'Stok tidak boleh negatif.',
            'kondisi_barang.required'  => 'Kondisi barang wajib dipilih.',
            'kondisi_barang.in'        => 'Kondisi barang harus Baik atau Rusak.',
            'foto_barang.required'     => 'Foto barang wajib diunggah.',
            'foto_barang.image'        => 'File harus berupa gambar.',
            'foto_barang.mimes'        => 'Format gambar harus jpeg, png, atau jpg.',
            'foto_barang.max'          => 'Ukuran gambar maksimal 2MB.',
        ]);

        // 2. Ambil hanya field yang diizinkan (hindari mass assignment)
        $data = $request->only([
            'nama_barang',
            'kategori_barang',
            'stok',
            'kondisi_barang',
        ]);

        // 3. Proses upload foto
        try {
            $uploadPath = public_path('uploads/barang');

            // Buat folder otomatis jika belum ada
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file      = $request->file('foto_barang');
            // Nama file unik: timestamp + uniqid + ekstensi asli
            $filename  = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $data['foto_barang'] = $filename;

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengunggah foto: ' . $e->getMessage());
        }

        // 4. Simpan ke database
        try {
            Barang::create($data);
        } catch (\Exception $e) {
            // Hapus foto yang sudah terupload jika gagal simpan ke DB
            if (isset($filename) && File::exists(public_path('uploads/barang/' . $filename))) {
                File::delete(public_path('uploads/barang/' . $filename));
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data barang: ' . $e->getMessage());
        }

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Memperbarui data barang yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        // 1. Validasi input
        $request->validate([
            'nama_barang'     => 'required|string|max:255',
            'kategori_barang' => 'required|string|max:255',
            'stok'            => 'required|integer|min:0',
            'kondisi_barang'  => 'required|in:Baik,Rusak',
            'foto_barang'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_barang.required'     => 'Nama barang wajib diisi.',
            'kategori_barang.required' => 'Kategori barang wajib diisi.',
            'stok.required'            => 'Stok wajib diisi.',
            'stok.integer'             => 'Stok harus berupa angka.',
            'stok.min'                 => 'Stok tidak boleh negatif.',
            'kondisi_barang.required'  => 'Kondisi barang wajib dipilih.',
            'kondisi_barang.in'        => 'Kondisi barang harus Baik atau Rusak.',
            'foto_barang.image'        => 'File harus berupa gambar.',
            'foto_barang.mimes'        => 'Format gambar harus jpeg, png, atau jpg.',
            'foto_barang.max'          => 'Ukuran gambar maksimal 2MB.',
        ]);

        // 2. Ambil hanya field yang diizinkan
        $data = $request->only([
            'nama_barang',
            'kategori_barang',
            'stok',
            'kondisi_barang',
        ]);

        // 3. Proses ganti foto jika ada file baru
        if ($request->hasFile('foto_barang')) {
            try {
                $uploadPath = public_path('uploads/barang');

                // Buat folder otomatis jika belum ada
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                // Hapus foto lama jika ada dan file-nya masih ada di disk
                if ($barang->foto_barang && File::exists($uploadPath . '/' . $barang->foto_barang)) {
                    File::delete($uploadPath . '/' . $barang->foto_barang);
                }

                // Upload foto baru
                $file     = $request->file('foto_barang');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $data['foto_barang'] = $filename;

            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal mengganti foto: ' . $e->getMessage());
            }
        }

        // 4. Update data di database
        try {
            $barang->update($data);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data barang: ' . $e->getMessage());
        }

        return redirect()->route('barang.index')
            ->with('success', 'Data barang berhasil diperbarui!');
    }

    /**
     * Menghapus data barang beserta fotonya.
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        try {
            // Hapus foto dari disk jika ada
            if ($barang->foto_barang) {
                $fotoPath = public_path('uploads/barang/' . $barang->foto_barang);
                if (File::exists($fotoPath)) {
                    File::delete($fotoPath);
                }
            }

            $barang->delete();

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }
}
