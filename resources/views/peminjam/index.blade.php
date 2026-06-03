@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <span>👥 Data Peminjam</span>
        <small style="font-size: 12px; color: #666;">Data dari Seeder (Tidak bisa diubah)</small>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Peminjam</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>No HP</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjams as $key => $peminjam)
                <tr>
                    <td>{{ $peminjams->firstItem() + $key }}</td>
                    <td>{{ $peminjam->nama_peminjam }}</td>
                    <td>{{ $peminjam->kelas }}</td>
                    <td>{{ $peminjam->jurusan }}</td>
                    <td>{{ $peminjam->no_hp }}</td>
                    <td>
                        <a href="{{ route('peminjams.show', $peminjam->id) }}" class="btn btn-info btn-sm">
                            📋 Detail & Riwayat
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $peminjams->links() }}
</div>
@endsection
