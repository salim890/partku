@extends('layouts.app')

@section('title', 'Diagram Part')

@push('styles')
<style>
    body { background-color: #f4f4f4; }
    
    /* Desain Container Katalog */
    .catalog-container { background: #fff; border: 1px solid #ddd; padding: 20px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .catalog-header { border-bottom: 1px solid #ddd; padding-bottom: 15px; margin-bottom: 20px; }
    .catalog-title { font-size: 1.2rem; font-weight: bold; color: #333; text-transform: uppercase; }
    
    /* Gambar Diagram */
    .diagram-image-wrapper { text-align: center; padding: 20px; border: 1px solid #eee; background: #fafafa; }
    .diagram-image-wrapper img { max-width: 100%; height: auto; }
    
    /* Tabel Part (Gaya Honda Cengkareng) */
    .table-catalog { font-size: 0.85rem; margin-bottom: 0; }
    .table-catalog th { background: #f1f1f1; color: #333; font-weight: 600; border-bottom: 2px solid #ccc; }
    .table-catalog td { vertical-align: middle; border-color: #eee; }
    
    /* Baris Tabel yang bisa diklik */
    .part-row { cursor: pointer; transition: 0.2s; }
    .part-row:hover { background-color: #fff3f0 !important; }
    .part-row.active { background-color: #ffe6e0 !important; border-left: 3px solid #cc0000; }
    
    /* Kotak Detail yang muncul di bawah */
    .part-detail-box { background: #f8f9fa; border: 1px dashed #ccc; padding: 20px; margin-top: 20px; border-radius: 4px; display: none; }
</style>
@endpush

@section('content')

<div class="container py-5">
    
    {{-- Judul Katalog --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Katalog Suku Cadang</a></li>
            <li class="breadcrumb-item active text-danger">E-17 : Driven Face - Honda Beat/Vario</li>
        </ol>
    </nav>

    <h4 class="mb-4 fw-bold">E-17 – Driven Face – Katalog Honda Beat / Vario</h4>

    <div class="row g-4">
        
        {{-- KIRI: Gambar Diagram --}}
        <div class="col-lg-7">
            <div class="catalog-container">
                <div class="catalog-header text-center">
                    <div class="catalog-title">E-17 &nbsp;&nbsp; DRIVEN FACE</div>
                </div>
                <div class="diagram-image-wrapper">
                    {{-- Pastikan file diagram-honda.png ada di folder public/images --}}
                    <img src="{{ asset('images/diagram-honda.png') }}" alt="Diagram Driven Face">
                </div>
                <div class="alert alert-warning mt-3 mb-0 small">
                    <i class="bi bi-info-circle-fill"></i> <strong>Panduan:</strong> Silakan lihat nomor referensi pada gambar, lalu klik baris tabel di sebelah kanan untuk melihat detail part.
                </div>
            </div>
        </div>

        {{-- KANAN: Tabel Part --}}
        <div class="col-lg-5">
            <div class="catalog-container">
                <div class="catalog-header">
                    <div class="catalog-title">DAFTAR KOMPONEN</div>
                </div>
                
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-catalog table-hover">
                        <thead class="sticky-top">
                            <tr>
                                <th width="40">No</th>
                                <th>No. Part</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data Dummy disamakan dengan gambar tabel Cengkareng --}}
                            <tr class="part-row" onclick="showDetail('1', '22100-KWN-900', 'OUTER COMP., CLUTCH')">
                                <td>1</td>
                                <td>22100-KWN-900</td>
                                <td>OUTER COMP., CLUTCH</td>
                            </tr>
                            <tr class="part-row" onclick="showDetail('2', '22550-K97-T01', 'PLATE COMP., DRIVE')">
                                <td>2</td>
                                <td>22550-K97-T01</td>
                                <td>PLATE COMP., DRIVE</td>
                            </tr>
                            <tr class="part-row" onclick="showDetail('3', '22361-KVB-900', 'PLATE, CLUTCH SIDE')">
                                <td>3</td>
                                <td>22361-KVB-900</td>
                                <td>PLATE, CLUTCH SIDE</td>
                            </tr>
                            <tr class="part-row" onclick="showDetail('4', '22401-KWN-640', 'SPRING, CLUTCH')">
                                <td>4</td>
                                <td>22401-KWN-640</td>
                                <td>SPRING, CLUTCH</td>
                            </tr>
                            <tr class="part-row" onclick="showDetail('5', '22535-K12-V00', 'WEIGHT SET, CLUTCH')">
                                <td>5</td>
                                <td>22535-K12-V00</td>
                                <td>WEIGHT SET, CLUTCH (Roller)</td>
                            </tr>
                            <tr class="part-row" onclick="showDetail('6', '22804-148-000', 'RUBBER, CLUTCH DAMPER')">
                                <td>6</td>
                                <td>22804-148-000</td>
                                <td>RUBBER, CLUTCH DAMPER</td>
                            </tr>
                            <tr class="part-row" onclick="showDetail('7', '23100-K36-J01', 'BELT, DRIVE (V-Belt)')">
                                <td>7</td>
                                <td>23100-K36-J01</td>
                                <td>BELT, DRIVE (V-Belt)</td>
                            </tr>
                            <tr class="part-row" onclick="showDetail('8', '23205-K59-A70', 'FACE SET, DRIVEN')">
                                <td>8</td>
                                <td>23205-K59-A70</td>
                                <td>FACE SET, DRIVEN</td>
                            </tr>
                            <tr class="part-row" onclick="showDetail('9', '23224-K59-A70', 'FACE SET, MOVABLE DRIVEN')">
                                <td>9</td>
                                <td>23224-K59-A70</td>
                                <td>FACE SET, MOVABLE DRIVEN</td>
                            </tr>
                            <tr class="part-row" onclick="showDetail('10', '23225-KSY-900', 'PIN, ROLLER GUIDE')">
                                <td>10</td>
                                <td>23225-KSY-900</td>
                                <td>PIN, ROLLER GUIDE</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Kotak Detail yang muncul saat tabel diklik --}}
                <div id="detailBox" class="part-detail-box">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0" id="detailTitle">-</h6>
                        <span class="badge bg-danger" id="detailBadge">No. -</span>
                    </div>
                    <p class="text-muted small mb-3" id="detailDesc">Deskripsi</p>
                    <a href="{{ route('katalog', ['search' => '']) }}" id="detailBtn" class="btn btn-danger btn-sm w-100">
                        <i class="bi bi-cart-plus"></i> Cari Part Ini di Toko
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function showDetail(no, partNumber, desc) {
        // 1. Highlight baris tabel yang diklik
        document.querySelectorAll('.part-row').forEach(row => row.classList.remove('active'));
        event.currentTarget.classList.add('active');

        // 2. Tampilkan kotak detail
        const box = document.getElementById('detailBox');
        box.style.display = 'block';

        // 3. Isi datanya
        document.getElementById('detailBadge').innerText = 'No. ' + no;
        document.getElementById('detailTitle').innerText = partNumber;
        document.getElementById('detailDesc').innerText = desc;

        // 4. Ubah link tombol beli (mencari part number tersebut di halaman katalog)
        document.getElementById('detailBtn').href = "{{ route('katalog') }}?search=" + partNumber;
    }
</script>
@endpush

@endsection