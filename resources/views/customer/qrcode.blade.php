@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h4 class="mb-0"><i class="mdi mdi-check-circle"></i> Pembayaran Berhasil!</h4>
            </div>
            <div class="card-body text-center">

                <p class="text-muted mb-1">Tunjukkan QR Code ini ke vendor</p>
                <p class="text-muted small mb-3">ID Pesanan: <strong>#{{ $pesanan->idpesanan }}</strong></p>

                {{-- QR Code --}}
                <div class="d-flex justify-content-center mb-3">
                    <div id="qrcode-container" style="padding:16px; background:#fff; display:inline-block; border-radius:8px; border:2px solid #28a745;">
                        <div id="qrcode"></div>
                    </div>
                </div>

                {{-- Info pesanan --}}
                <div class="card bg-light mt-3 text-left">
                    <div class="card-body py-2 px-3">
                        <small class="text-muted">Nama Pemesan</small>
                        <p class="mb-1 font-weight-bold">{{ $pesanan->nama }}</p>
                        <small class="text-muted">Total Bayar</small>
                        <p class="mb-1 font-weight-bold">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
                        <small class="text-muted">Metode Bayar</small>
                        <p class="mb-0 font-weight-bold">
                            {{ $pesanan->metode_bayar == 1 ? 'Cash' : 'Transfer' }}
                        </p>
                    </div>
                </div>

                <p class="text-muted small mt-3 mb-0">
                    <i class="mdi mdi-information-outline"></i>
                    QR Code ini tersimpan otomatis dan bisa diakses kembali kapan saja.
                </p>

            </div>
            <div class="card-footer text-center">
                <a href="{{ route('customer.pesanan') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="mdi mdi-arrow-left"></i> Kembali ke Pesanan
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
        .navbar, .sidebar, .footer, .card-footer, .btn { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
    }
</style>
@endpush

@push('scripts')
{{-- Library QRCode.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    const idPesanan = "{{ $pesanan->idpesanan }}";

    // Generate QR Code berisi idpesanan
    new QRCode(document.getElementById("qrcode"), {
        text: idPesanan,
        width: 220,
        height: 220,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    // Simpan ke localStorage agar bisa diakses lagi setelah halaman ditutup
    localStorage.setItem('last_qr_pesanan', idPesanan);
    localStorage.setItem('last_qr_url', window.location.href);
</script>
@endpush