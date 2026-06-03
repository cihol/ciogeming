<th>No</th>
<th>Foto</th>
<th>Nama Barang</th>
...

<td>
    @if($item->foto_barang)
        <img src="{{ asset('uploads/barang/'.$item->foto_barang) }}" width="60" class="img-thumbnail">
    @else
        <span class="text-muted small">No Photo</span>
    @endif
</td>

@if(Auth::user()->role === 'Admin')
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">+ Tambah Barang</button>
@endif

<form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
...
<div class="mb-3">
    <label class="form-label">Upload Foto Barang</label>
    <input type="file" name="foto_barang" class="form-control" accept="image/*">
</div>
