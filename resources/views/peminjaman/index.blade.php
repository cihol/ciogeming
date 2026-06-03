@extends('layouts.app')

@section('content')
<style>
/* ==========================
   CRUD PEMINJAMAN
   BLUE - WHITE - GRAY THEME
========================== */

body{
    background: #f4f7fc;
    font-family: 'Poppins', sans-serif;
}

/* Card Utama */
.card{
    border: none !important;
    border-radius: 18px;
    overflow: hidden;
}

.card-header{
    background: #ffffff !important;
    border-bottom: 1px solid #e9ecef;
}

.card-header h5{
    color: #1e3a5f !important;
    font-weight: 700;
}

/* Tombol */
.btn-primary{
    background: #0d6efd;
    border: none;
    border-radius: 10px;
    font-weight: 500;
    transition: .3s;
}

.btn-primary:hover{
    background: #0b5ed7;
    transform: translateY(-2px);
}

.btn-warning{
    border-radius: 8px;
    font-weight: 500;
}

.btn-danger{
    border-radius: 8px;
    font-weight: 500;
}

.btn-secondary{
    border-radius: 8px;
}

/* Tabel */
.table{
    margin-bottom: 0;
}

.table thead{
    background: #f8f9fa;
}

.table thead th{
    color: #1e3a5f;
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
    padding: 14px;
    white-space: nowrap;
}

.table tbody td{
    padding: 14px;
    vertical-align: middle;
}

.table-hover tbody tr:hover{
    background: #eef5ff;
}

/* Badge */
.badge{
    border-radius: 8px;
    padding: 7px 10px;
    font-weight: 500;
}

.bg-warning{
    background-color: #ffc107 !important;
}

.bg-success{
    background-color: #198754 !important;
}

.bg-danger{
    background-color: #dc3545 !important;
}

/* Nama Peminjam */
.fw-semibold{
    color: #1e3a5f;
}

/* Modal */
.modal-content{
    border: none;
    border-radius: 18px;
    overflow: hidden;
}

.modal-header{
    background: linear-gradient(
        135deg,
        #0d6efd,
        #4d9cff
    );
    color: white;
    border: none;
}

.modal-header .btn-close{
    filter: brightness(0) invert(1);
}

.modal-title{
    font-weight: 600;
}

.modal-footer{
    border-top: 1px solid #ececec;
}

/* Form */
.form-label{
    font-weight: 500;
    color: #495057;
}

.form-control,
.form-select{
    border-radius: 10px;
    border: 1px solid #ced4da;
    padding: 10px 12px;
}

.form-control:focus,
.form-select:focus{
    border-color: #0d6efd;
    box-shadow: 0 0 0 .15rem rgba(13,110,253,.15);
}

/* Info Box Modal */
.bg-light{
    background: #f8f9fa !important;
    border-radius: 10px;
}

/* Empty Data */
.text-muted{
    color: #6c757d !important;
}

/* Shadow */
.shadow-sm{
    box-shadow: 0 4px 18px rgba(0,0,0,.06) !important;
}

/* Responsive */
@media (max-width:768px){

    .card-header{
        flex-direction: column;
        align-items: flex-start !important;
        gap: 10px;
    }

    .table{
        font-size: 14px;
    }

    .btn{
        font-size: 13px;
    }

    .modal-dialog{
        margin: 1rem;
    }
}
</style>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-secondary">CRUD Transaksi Peminjaman</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPeminjaman">
            + Buat Peminjaman
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Peminjam</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $pinjam)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="fw-semibold">{{ $pinjam->peminjam->nama_peminjam }}</span>
                            <div class="small text-muted">{{ $pinjam->peminjam->kelas }} {{ $pinjam->peminjam->jurusan }}</div>
                        </td>
                        <td>{{ $pinjam->barang->nama_barang }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $pinjam->jumlah_pinjam }} unit</span></td>
                        <td>{{ $pinjam->tanggal_pinjam }}</td>
                        <td>{{ $pinjam->tanggal_kembali ?? '-' }}</td>
                        <td>
                            @if($pinjam->status_peminjaman == 'Dipinjam')
                                <span class="badge bg-warning text-dark">Dipinjam</span>
                            @elseif($pinjam->status_peminjaman == 'Dikembalikan')
                                <span class="badge bg-success">Dikembalikan</span>
                            @else
                                <span class="badge bg-danger">Terlambat</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#modalEditPeminjaman{{ $pinjam->id }}">Ubah</button>

                            <form action="{{ route('peminjaman.destroy', $pinjam->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Menghapus data ini akan mempengaruhi riwayat/stok. Lanjutkan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEditPeminjaman{{ $pinjam->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('peminjaman.update', $pinjam->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ubah Transaksi Peminjaman</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3 bg-light p-2 rounded border">
                                            <small class="text-muted d-block">Peminjam:</small>
                                            <strong>{{ $pinjam->peminjam->nama_peminjam }}</strong>
                                            <small class="text-muted d-block mt-1">Barang yang dipinjam:</small>
                                            <strong>{{ $pinjam->barang->nama_barang }} ({{ $pinjam->jumlah_pinjam }} unit)</strong>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status Peminjaman</label>
                                            <select name="status_peminjaman" class="form-select" required>
                                                <option value="Dipinjam" {{ $pinjam->status_peminjaman == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                                <option value="Dikembalikan" {{ $pinjam->status_peminjaman == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                                <option value="Terlambat" {{ $pinjam->status_peminjaman == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                                            </select>
                                            <small class="text-muted mt-1 d-block">* Mengubah status ke 'Dikembalikan' otomatis memulihkan stok barang.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-warning text-white">Update Status</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-3">Belum ada transaksi peminjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahPeminjaman" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('peminjaman.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Form Input Peminjaman Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Peminjam (Siswa)</label>
                        <select name="peminjam_id" class="form-select" required>
                            <option value="">-- Pilih Anggota --</option>
                            @foreach($peminjam as $mhs)
                                <option value="{{ $mhs->id }}">{{ $mhs->nama_peminjam }} ({{ $mhs->kelas }} {{ $mhs->jurusan }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih Barang</label>
                        <select name="barang_id" class="form-select" required>
                            <option value="">-- Pilih Unit Barang --</option>
                            @foreach($barang as $b)
                                <option value="{{ $b->id }}">{{ $b->nama_barang }} (Sedia: {{ $b->stok }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Pinjam</label>
                        <input type="number" name="jumlah_pinjam" class="form-control" min="1" placeholder="Masukkan jumlah" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Proses Peminjaman</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
