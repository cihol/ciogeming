@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-secondary">
                <i class="bi bi-people me-2"></i>Data Peminjam (View Only)
            </h5>
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
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjams as $item)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $item->nama_peminjam }}</td>
                            <td>{{ $item->kelas }}</td>
                            <td>{{ $item->no_hp ?? '-' }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
                                    <i class="bi bi-eye"></i> Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada data peminjam.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail Saja --}}
@foreach($peminjams as $item)
<div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Peminjam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Nama:</strong> {{ $item->nama_peminjam }}</p>
                <p><strong>Kelas:</strong> {{ $item->kelas }}</p>
                <p><strong>No. HP:</strong> {{ $item->no_hp }}</p>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
