@extends('layouts.app')

@section('title', 'Tambah Customer (File)')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('customer.index') }}" class="btn btn-sm btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h5 class="fw-bold mb-0">Tambah Customer 2 — Simpan Foto sebagai File</h5>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">

                    {{-- KAMERA --}}
                    <div class="mb-3 text-center">
                        <video id="video" autoplay playsinline
                               class="rounded border w-100"
                               style="max-height:300px; background:#000;">
                        </video>
                        <canvas id="canvas" hidden></canvas>
                    </div>

                    <div class="d-flex gap-2 mb-3 justify-content-center">
                        <button type="button" class="btn btn-outline-success" onclick="startCamera()">
                            <i class="bi bi-camera-video"></i> Buka Kamera
                        </button>
                        <button type="button" class="btn btn-success" id="btnCapture" onclick="ambilFoto()" disabled>
                            <i class="bi bi-camera"></i> Ambil Foto
                        </button>
                    </div>

                    {{-- PREVIEW --}}
                    <div class="text-center mb-3" id="previewWrap" style="display:none!important">
                        <p class="small text-muted mb-1">Preview foto:</p>
                        <img id="preview" class="rounded border"
                             style="max-width:200px; max-height:200px; object-fit:cover;" alt="preview">
                    </div>

                    {{-- FORM --}}
                    <form action="{{ route('customer.store2') }}" method="POST">
                        @csrf
                        <input type="hidden" name="foto_data" id="foto_data">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control"
                                   value="{{ old('nama') }}" placeholder="Nama customer" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email') }}" placeholder="email@contoh.com">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">No. HP</label>
                            <input type="text" name="no_hp" class="form-control"
                                   value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success" id="btnSimpan" disabled>
                                <i class="bi bi-save"></i> Simpan Customer
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <div class="alert alert-info mt-3 small">
                <i class="bi bi-info-circle"></i>
                Foto akan disimpan sebagai file di <code>storage/app/public/customers/</code>
                dan path-nya disimpan ke database.
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let stream = null;

    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            const video = document.getElementById('video');
            video.srcObject = stream;
            document.getElementById('btnCapture').disabled = false;
        } catch (err) {
            alert('Tidak bisa mengakses kamera: ' + err.message);
        }
    }

    function ambilFoto() {
        const video   = document.getElementById('video');
        const canvas  = document.getElementById('canvas');
        const preview = document.getElementById('preview');

        canvas.width  = video.videoWidth  || 640;
        canvas.height = video.videoHeight || 480;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
        document.getElementById('foto_data').value = dataUrl;
        preview.src = dataUrl;

        const wrap = document.getElementById('previewWrap');
        wrap.style.removeProperty('display');
        wrap.style.display = 'block';

        document.getElementById('btnSimpan').disabled = false;

        if (stream) {
            stream.getTracks().forEach(t => t.stop());
        }
    }
</script>
@endpush