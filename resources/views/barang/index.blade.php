@extends('layouts.app')

@section('content')

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-secondary">CRUD Data Barang</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
            + Tambah Barang
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Kondisi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $item->nama_barang }}</td>
                        <td><span class="badge bg-secondary">{{ $item->kategori_barang }}</span></td>
                        <td>{{ $item->stok }}</td>
                        <td>{{ $item->kondisi_barang }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalDetailBarang{{ $item->id }}">Detail</button>

                            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#modalEditBarang{{ $item->id }}">Ubah</button>

                            <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">Data barang masih kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ✅ Modal diletakkan di LUAR tabel agar struktur HTML valid --}}
@foreach($barang as $item)

{{-- Modal Detail --}}
<div class="modal fade" id="modalDetailBarang{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Barang: {{ $item->nama_barang }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Nama:</strong> {{ $item->nama_barang }}</li>
                    <li class="list-group-item"><strong>Kategori:</strong> {{ $item->kategori_barang }}</li>
                    <li class="list-group-item"><strong>Stok:</strong> {{ $item->stok }}</li>
                    <li class="list-group-item"><strong>Kondisi:</strong> {{ $item->kondisi_barang }}</li>
                    {{-- ✅ FIX: optional() mencegah error jika created_at null --}}
                    <li class="list-group-item">
                        <strong>Dibuat pada:</strong>
                        {{ optional($item->created_at)->format('d M Y') ?? '-' }}
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEditBarang{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- ✅ FIX: tambahkan enctype untuk upload foto --}}
            <form action="{{ route('barang.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Data Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" value="{{ $item->nama_barang }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="kategori_barang" class="form-control" value="{{ $item->kategori_barang }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" value="{{ $item->stok }}" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kondisi Barang</label>
                        <select name="kondisi_barang" class="form-select" required>
                            <option value="Baik" {{ $item->kondisi_barang == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak" {{ $item->kondisi_barang == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- ✅ FIX: tambahkan enctype untuk upload foto --}}
            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: PC Desktop" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="kategori_barang" class="form-control" placeholder="Contoh: Elektronik" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok Awal</label>
                        <input type="number" name="stok" class="form-control" min="0" placeholder="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kondisi Barang</label>
                        <select name="kondisi_barang" class="form-select" required>
                            <option value="Baik" selected>Baik</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
