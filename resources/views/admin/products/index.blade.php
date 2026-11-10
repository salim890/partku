@extends('layouts.admin')
@section('title', 'Kelola Produk')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Kelola Produk</h3>
        <a href="{{ route('admin.products.create') }}" class="btn btn-danger fw-bold"><i class="bi bi-plus-lg"></i> Tambah Produk Baru</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kode Part</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                    <tr>
                        <td><img src="{{ $p->image_url }}" style="width: 60px; height: 60px; object-fit: contain;" class="border rounded"></td>
                        <td>
                            <strong>{{ $p->name }}</strong><br>
                            <small class="text-muted">{{ $p->category }}</small>
                        </td>
                        <td>{{ $p->part_number }}</td>
                        <td>Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                        <td>{{ $p->stock }}</td>
                        <td>
                            <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection