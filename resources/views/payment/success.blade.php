@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">

        {{-- Header --}}
        <div class="text-center mb-4">
            <div style="font-size: 60px;">✅</div>
            <h2 class="fw-bold text-success mt-2">Pembayaran Berhasil!</h2>
            <p class="text-muted">Terima kasih, pesanan Anda sedang diproses.</p>
        </div>

        {{-- Card Info Pesanan --}}
        <div class="card mb-4" style="border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            <div class="card-body p-4">

                <h5 class="fw-bold mb-3">Detail Pesanan</h5>

                <div class="d-flex justify-content-between border-bottom py-2">
                    <span class="text-muted">ID Pesanan</span>
                    <span class="fw-bold">#{{ $pesanan->idpesanan }}</span>
                </div>

                <div class="d-flex justify-content-between border-bottom py-2">
                    <span class="text-muted">Nama</span>
                    <span class="fw-bold">{{ $pesanan->nama }}</span>
                </div>

                <div class="d-flex justify-content-between border-bottom py-2">
                    <span class="text-muted">Total Bayar</span>
                    <span class="fw-bold text-success">
                        Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                    </span>
                </div>

                <div class="d-flex justify-content-between border-bottom py-2">
                    <span class="text-muted">Metode Bayar</span>
                    <span class="fw-bold text-uppercase">{{ $pesanan->metode_bayar }}</span>
                </div>

                <div class="d-flex justify-content-between border-bottom py-2">
                    <span class="text-muted">Status</span>
                    <span class="badge bg-success px-3 py-2">LUNAS</span>
                </div>

                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted">Waktu</span>
                    <span>{{ \Carbon\Carbon::parse($pesanan->timestamp)->format('d M Y, H:i') }}</span>
                </div>

            </div>
        </div>

        {{-- QR Code --}}
        <div class="card mb-4" style="border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            <div class="card-body p-4 text-center">

                <h5 class="fw-bold mb-1">QR Code Pesanan</h5>
                <p class="text-muted small mb-3">
                    Tunjukkan QR Code ini kepada vendor sebagai bukti pembayaran.
                </p>

                <div style="background:#fff; border: 2px dashed #198754; border-radius: 12px; padding: 20px; display: inline-block;">
                    <img src="{{ $qrDataUri }}" 
                         alt="QR Code #{{ $pesanan->idpesanan }}" 
                         style="width: 200px; height: 200px;">
                </div>

                <p class="mt-3 text-muted small">
                    ID: <strong>#{{ $pesanan->idpesanan }}</strong>
                </p>

            </div>
        </div>

        {{-- Tombol --}}
        <div class="d-grid gap-2">
            <a href="{{ route('home') }}" class="btn btn-success btn-lg">
                🏠 Kembali ke Halaman Utama
            </a>
            <button onclick="window.print()" class="btn btn-outline-secondary">
                🖨️ Print / Simpan sebagai PDF
            </button>
        </div>

    </div>
</div>
@endsection