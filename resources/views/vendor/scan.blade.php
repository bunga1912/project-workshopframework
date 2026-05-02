@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="mdi mdi-qrcode-scan"></i> Scan QR Code Customer</h4>
            </div>
            <div class="card-body text-center">

                {{-- Area kamera scanner --}}
                <div id="reader" style="width:100%; max-width:500px; margin:0 auto;"></div>

                {{-- Tombol kontrol --}}
                <div class="mt-3">
                    <button id="btn-start" class="btn btn-success px-4" onclick="startScanner()">
                        <i class="mdi mdi-play"></i> Mulai Scan
                    </button>
                    <button id="btn-stop" class="btn btn-danger px-4 d-none" onclick="stopScanner()">
                        <i class="mdi mdi-stop"></i> Stop Scan
                    </button>
                </div>

                {{-- Upload gambar QR --}}
                <div class="mt-3">
                    <p class="text-muted small mb-1">Atau scan dari file gambar:</p>
                    <label for="input-gambar" class="btn btn-outline-primary btn-sm">
                        <i class="mdi mdi-image"></i> Upload Gambar QR Code
                    </label>
                    <input type="file" id="input-gambar" accept="image/*" class="d-none">
                </div>

                {{-- Status scanning --}}
                <div id="scan-status" class="mt-3 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Arahkan kamera ke QR Code customer...</p>
                </div>

            </div>
        </div>

        {{-- Card hasil scan: data pesanan --}}
        <div id="card-hasil" class="card shadow mt-4 d-none">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="mdi mdi-check-circle"></i>
                    Pesanan Ditemukan — ID #<span id="result-idpesanan"></span>
                </h5>
            </div>
            <div class="card-body">

                {{-- Info umum pesanan --}}
                <table class="table table-bordered mb-3">
                    <tr>
                        <th width="35%">Nama Pemesan</th>
                        <td id="result-nama">-</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td id="result-total">-</td>
                    </tr>
                    <tr>
                        <th>Status Bayar</th>
                        <td id="result-status">-</td>
                    </tr>
                </table>

                {{-- Tabel menu yang dipesan --}}
                <h6 class="font-weight-bold mb-2">Menu yang Dipesan:</h6>
                <div id="result-menu">
                    {{-- Diisi via JavaScript --}}
                </div>

                <div class="mt-3 text-center">
                    <button class="btn btn-primary" onclick="resetScanner()">
                        <i class="mdi mdi-refresh"></i> Scan QR Lain
                    </button>
                </div>
            </div>
        </div>

        {{-- Card pesanan tidak ditemukan --}}
        <div id="card-notfound" class="card shadow mt-4 d-none">
            <div class="card-body text-center text-danger">
                <i class="mdi mdi-alert-circle mdi-48px"></i>
                <p class="mt-2 mb-0">Pesanan tidak ditemukan atau QR tidak valid.</p>
                <button class="btn btn-outline-danger mt-3" onclick="resetScanner()">
                    <i class="mdi mdi-refresh"></i> Scan Ulang
                </button>
            </div>
        </div>

    </div>
</div>

{{-- Audio beep --}}
<audio id="beep-sound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>
@endsection

@push('scripts')
{{-- Library html5-qrcode dari CDN --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    let html5QrCode = null;
    let isScanning = false;

    function startScanner() {
        html5QrCode = new Html5Qrcode("reader");

        document.getElementById('btn-start').classList.add('d-none');
        document.getElementById('btn-stop').classList.remove('d-none');
        document.getElementById('scan-status').classList.remove('d-none');
        document.getElementById('card-hasil').classList.add('d-none');
        document.getElementById('card-notfound').classList.add('d-none');

        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess,
            onScanError
        ).catch(err => {
            alert('Tidak bisa mengakses kamera: ' + err);
            resetScanner();
        });

        isScanning = true;
    }

    function onScanSuccess(decodedText) {
        if (!isScanning) return;
        isScanning = false;

        // Bunyi beep
        document.getElementById('beep-sound').play();

        // Stop scanner kamera (kalau aktif)
        stopScanner();

        // Ambil data pesanan dari server berdasarkan idpesanan dari QR
        fetch(`/vendor/pesanan/${encodeURIComponent(decodedText)}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const p = data.pesanan;

                document.getElementById('result-idpesanan').textContent = p.idpesanan;
                document.getElementById('result-nama').textContent      = p.nama;
                document.getElementById('result-total').textContent     = 'Rp ' + parseInt(p.total).toLocaleString('id-ID');

                // Status bayar
                let statusBadge = p.status_bayar == 1
                    ? '<span class="badge badge-success">Lunas</span>'
                    : '<span class="badge badge-danger">Belum Bayar</span>';
                document.getElementById('result-status').innerHTML = statusBadge;

                // Tabel menu dengan kolom Qty dan Subtotal
                let menuHtml = `
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Menu</th>
                                <th class="text-center">Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>`;

                if (data.menu && data.menu.length > 0) {
                    data.menu.forEach(m => {
                        menuHtml += `<tr>
                            <td>${m.nama_menu}</td>
                            <td class="text-center">${m.jumlah}x</td>
                            <td>Rp ${parseInt(m.subtotal).toLocaleString('id-ID')}</td>
                        </tr>`;
                    });
                } else {
                    menuHtml += `
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Tidak ada menu dari vendor ini di pesanan ini
                                </td>
                            </tr>`;
                }

                menuHtml += '</tbody></table>';
                document.getElementById('result-menu').innerHTML = menuHtml;

                document.getElementById('card-hasil').classList.remove('d-none');
            } else {
                document.getElementById('card-notfound').classList.remove('d-none');
            }
        })
        .catch(() => {
            document.getElementById('card-notfound').classList.remove('d-none');
        });
    }

    function onScanError(error) {
        // Diabaikan, error ini normal saat kamera belum menemukan QR
    }

    function stopScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
            }).catch(() => {});
        }
        document.getElementById('btn-start').classList.remove('d-none');
        document.getElementById('btn-stop').classList.add('d-none');
        document.getElementById('scan-status').classList.add('d-none');
        isScanning = false;
    }

    function resetScanner() {
        document.getElementById('card-hasil').classList.add('d-none');
        document.getElementById('card-notfound').classList.add('d-none');
        document.getElementById('input-gambar').value = '';
        startScanner();
    }

    // ── Scan dari file gambar ──────────────────────────────
    document.getElementById('input-gambar').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        stopScanner();

        document.getElementById('card-hasil').classList.add('d-none');
        document.getElementById('card-notfound').classList.add('d-none');

        const scannerSementara = new Html5Qrcode("reader");

        scannerSementara.scanFile(file, true)
            .then(decodedText => {
                scannerSementara.clear();
                isScanning = true;
                onScanSuccess(decodedText);
            })
            .catch(() => {
                scannerSementara.clear();
                alert('QR Code tidak terbaca dari gambar. Coba gambar yang lebih jelas.');
                e.target.value = '';
            });
    });
</script>
@endpush