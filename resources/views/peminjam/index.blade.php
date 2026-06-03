@extends('layouts.app')

@section('content')

<div class="container-fluid px-4 py-4">

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold text-secondary">
                <i class="bi bi-people me-2"></i>CRUD Data Peminjam
            </h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPeminjam">
                <i class="bi bi-plus-lg me-1"></i>Tambah Peminjam
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Peminjam</th>
                            <th>Kelas</th>
                            <th>No. HP</th>
                            <th>Terdaftar</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjams as $item)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $item->nama_peminjam }}</td>
                            <td><span class="badge bg-secondary">{{ $item->kelas }}</span></td>
                            <td>{{ $item->no_hp ?? '-' }}</td>
                            <td>{{ optional($item->created_at)->format('d M Y') ?? '-' }}</td>
                            <td class="text-center pe-4">

                                {{-- Tombol Detail --}}
                                <button class="btn btn-sm btn-info text-white"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDetailPeminjam{{ $item->id }}">
                                    <i class="bi bi-eye"></i> Detail
                                </button>

                                {{-- Tombol Ubah --}}
                                <button class="btn btn-sm btn-warning text-white"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditPeminjam{{ $item->id }}">
                                    <i class="bi bi-pencil"></i> Ubah
                                </button>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('peminjam.destroy', $item->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus peminjam ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                Data peminjam masih kosong.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail & Edit di luar tabel --}}
@foreach($peminjams as $item)

{{-- Modal Detail --}}
<div class="modal fade" id="modalDetailPeminjam{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle me-2"></i>Detail Peminjam
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Nama Peminjam</strong>
                        <span>{{ $item->nama_peminjam }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Kelas</strong>
                        <span>{{ $item->kelas }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>No. HP</strong>
                        <span>{{ $item->no_hp ?? '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Terdaftar pada</strong>
                        <span>{{ optional($item->created_at)->format('d M Y') ?? '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Diperbarui pada</strong>
                        <span>{{ optional($item->updated_at)->format('d M Y') ?? '-' }}</span>
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
<div class="modal fade" id="modalEditPeminjam{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('peminjam.update', $item->id) }}"
                  method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>Ubah Data Peminjam
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Peminjam</label>
                        <input type="text"
                               name="nama_peminjam"
                               class="form-control"
                               value="{{ $item->nama_peminjam }}"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kelas</label>
                        <input type="text"
                               name="kelas"
                               class="form-control"
                               value="{{ $item->kelas }}"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. HP <small class="text-muted">(opsional)</small></label>
                        <input type="text"
                               name="no_hp"
                               class="form-control"
                               value="{{ $item->no_hp }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach

{{-- Modal Tambah Peminjam --}}
<div class="modal fade" id="modalTambahPeminjam" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('peminjam.store') }}"
                  method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus me-2"></i>Tambah Peminjam Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Peminjam</label>
                        <input type="text"
                               name="nama_peminjam"
                               class="form-control"
                               placeholder="Contoh: Budi Santoso"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kelas</label>
                        <input type="text"
                               name="kelas"
                               class="form-control"
                               placeholder="Contoh: XII RPL 1"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. HP <small class="text-muted">(opsional)</small></label>
                        <input type="text"
                               name="no_hp"
                               class="form-control"
                               placeholder="Contoh: 08123456789">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
