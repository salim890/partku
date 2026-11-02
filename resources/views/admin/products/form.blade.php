@extends('layouts.app')
@section('title', isset($product) ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-bold mb-4">{{ isset($product) ? 'Edit Produk' : 'Tambah Produk Baru' }}</h4>
                
                <form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($product)) @method('PUT') @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Produk</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kode Part (Part Number)</label>
                            <input type="text" name="part_number" class="form-control" value="{{ old('part_number', $product->part_number ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="category" class="form-select" required>
                                <option value="Mesin" {{ (isset($product) && $product->category == 'Mesin') ? 'selected' : '' }}>Mesin</option>
                                <option value="CVT" {{ (isset($product) && $product->category == 'CVT') ? 'selected' : '' }}>CVT</option>
                                <option value="Pengereman" {{ (isset($product) && $product->category == 'Pengereman') ? 'selected' : '' }}>Pengereman</option>
                                <option value="Kelistrikan" {{ (isset($product) && $product->category == 'Kelistrikan') ? 'selected' : '' }}>Kelistrikan</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Harga (Rp)</label>
                            <input type="number" name="price" class="form-control" value="{{ old('price', $product->price ?? '') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Stok</label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock ?? '') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Kompatibel Motor (Pisahkan dengan koma)</label>
                        <input type="text" name="motor_models" class="form-control" placeholder="Contoh: Beat, Vario, Scoopy" value="{{ old('motor_models', $product->motor_models ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Gambar Produk (Opsional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Format: JPG/PNG. Sistem akan otomatis menamai file sesuai Kode Part.</small>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="is_popular" id="is_popular" {{ (isset($product) && $product->is_popular) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="is_popular">Tampilkan di Halaman Home (Produk Populer)</label>
                    </div>

                    <button type="submit" class="btn btn-danger w-100 fw-bold py-2">Simpan Produk</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light w-100 mt-2">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection