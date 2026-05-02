@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h4 class="mb-0">
                    <i class="mdi mdi-check-circle"></i> Pembayaran Berhasil!
                </h4>
            </div>
            <div class="card-body text-center">

                <p class="text-muted mb-1">Tunjukkan QR Code ini ke vendor</p>
                <p class="text-muted small mb-3">
                    ID Pesanan: <strong>#{{ $pesanan->idpesanan }}</strong>
                </p>

                {{-- QR Code dari server (base64) --}}
                <div class="d-flex justify-content-center mb-3">
                    <div style="padding:16px; background:#fff; display:inline-block;
                                border-radius:8px; border:2px solid #28a745;">
                        <img src="{{ $qrDataUri }}"
                             alt="QR Code Pesanan #{{ $pesanan->idpesanan }}"
                             style="width:220px; height:220px;">
                    </div>
                </div>

                {{-- Info pesanan --}}
                <div class="card bg-light mt-3 text-left">
                    <div class="card-body py-2 px-3">
                        <div class="mb-2">
                            <small class="text-muted d-block">Nama Pemesan</small>
                            <span class="font-weight-bold">{{ $pesanan->nama }}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Total Bayar</small>
                            <span class="font-weight-bold">
                                Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted d-block">Metode Bayar</small>
                            <span class="font-weight-bold">
                                {{ $pesanan->metode_bayar == 1 ? 'Cash' : 'Transfer / Online' }}
                            </span>
                        </div>
                    </div>
                </div>

                <p class="text-muted small mt-3 mb-0">
                    <i class="mdi mdi-information-outline"></i>
                    Screenshot atau print halaman ini untuk menyimpan QR Code kamu.
                </p>

            </div>
            <div class="card-footer text-center">
                <a href="{{ route('pesanan.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="mdi mdi-arrow-left"></i> Pesan Lagi
                </a>
                <button class="btn btn-outline-success btn-sm ml-2" onclick="window.print()">
                    <i class="mdi mdi-printer"></i> Print QR
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        .navbar, .sidebar, .footer,
        .card-footer, .btn, .nav { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .main-panel { margin: 0 !important; }
    }
</style>
@endpush