@extends('layouts.app')

@section('title', 'Konsultasi Gratis')

@push('styles')
<style>
    body { background-color: #f4f4f4; }
    .chat-container { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; height: 600px; display: flex; flex-direction: column; }
    .chat-header { background: #cc0000; color: white; padding: 15px 20px; }
    .chat-body { flex-grow: 1; padding: 20px; overflow-y: auto; background: #f8f9fa; }
    .chat-bubble { max-width: 80%; padding: 12px 16px; border-radius: 8px; margin-bottom: 15px; font-size: 0.95rem; }
    .chat-user { background: #cc0000; color: white; margin-left: auto; border-bottom-right-radius: 0; }
    .chat-admin { background: #fff; border: 1px solid #ddd; color: #333; margin-right: auto; border-bottom-left-radius: 0; }
    .form-card { background: #fff; border-radius: 8px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="form-card">
                <h5 class="fw-bold mb-3"><i class="bi bi-clipboard-pulse text-danger"></i> Form Diagnosa</h5>
                <form action="{{ route('konsultasi.send') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Model Motor</label>
                        <select name="motor" class="form-select" required>
                            <option value="">-- Pilih Model --</option>
                            <option value="Beat">Honda Beat</option>
                            <option value="Vario">Honda Vario</option>
                            <option value="PCX">Honda PCX</option>
                            <option value="Scoopy">Honda Scoopy</option>
                            <option value="Genio">Honda Genio</option>
                            <option value="ADV">Honda ADV</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tahun Motor</label>
                        <input type="number" name="tahun" min="2015" max="2026" class="form-control" placeholder="Contoh: 2020" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Keluhan</label>
                        <textarea name="complaint" rows="4" class="form-control" placeholder="Ceritakan keluhan motormu..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-bold">Kirim ke Admin</button>
                </form>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="chat-container">
                <div class="chat-header d-flex justify-content-between align-items-center">
                    <div class="fw-bold"><i class="bi bi-headset"></i> PartKu Customer Service</div>
                    @if(count($chat) > 0)
                        <form action="{{ route('konsultasi.clear') }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-light text-danger fw-bold" style="font-size: 0.75rem;">Hapus Chat</button>
                        </form>
                    @endif
                </div>
                <div class="chat-body" id="chatBody">
                    @if(count($chat) == 0)
                        <div class="text-center text-muted mt-5">
                            <i class="bi bi-chat-dots" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mt-2">Belum ada percakapan. Silakan isi form diagnosa.</p>
                        </div>
                    @else
                        @foreach($chat as $msg)
                            @if($msg['from'] == 'user')
                                <div class="chat-bubble chat-user">
                                    <small class="d-block opacity-75 mb-1" style="font-size: 0.7rem;">{{ $msg['motor'] }} {{ $msg['tahun'] }} - {{ $msg['time'] }}</small>
                                    {{ $msg['message'] }}
                                </div>
                            @else
                                <div class="chat-bubble chat-admin">
                                    <small class="d-block text-muted mb-1" style="font-size: 0.7rem;">Admin - {{ $msg['time'] }}</small>
                                    {{ $msg['message'] }}
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    // Auto scroll ke bawah saat halaman dimuat
    var chatBody = document.getElementById("chatBody");
    chatBody.scrollTop = chatBody.scrollHeight;
</script>
@endpush
@endsection