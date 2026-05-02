@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="mdi mdi-barcode-scan"></i> Barcode Scanner</h4>
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

                {{-- Upload gambar barcode --}}
                <div class="mt-3">
                    <p class="text-muted small mb-1">Atau scan dari file gambar:</p>
                    <label for="input-gambar" class="btn btn-outline-primary btn-sm">
                        <i class="mdi mdi-image"></i> Upload Gambar Barcode
                    </label>
                    <input type="file" id="input-gambar" accept="image/*" class="d-none">
                </div>

                {{-- Status scan --}}
                <div id="scan-status" class="mt-3 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Arahkan kamera ke barcode...</p>
                </div>

            </div>
        </div>

        {{-- Card hasil scan --}}
        <div id="card-hasil" class="card shadow mt-4 d-none">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="mdi mdi-check-circle"></i> Hasil Scan</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th width="35%">ID Barang</th>
                        <td id="result-id">-</td>
                    </tr>
                    <tr>
                        <th>Nama Barang</th>
                        <td id="result-nama">-</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td id="result-harga">-</td>
                    </tr>
                </table>

                <div class="mt-3 text-center">
                    <button class="btn btn-primary" onclick="resetScanner()">
                        <i class="mdi mdi-refresh"></i> Scan Lagi
                    </button>
                </div>
            </div>
        </div>

        {{-- Card barang tidak ditemukan --}}
        <div id="card-notfound" class="card shadow mt-4 d-none">
            <div class="card-body text-center text-danger">
                <i class="mdi mdi-alert-circle mdi-48px"></i>
                <p class="mt-2 mb-0">Barang dengan ID <strong id="notfound-id"></strong> tidak ditemukan.</p>
                <button class="btn btn-outline-danger mt-3" onclick="resetScanner()">
                    <i class="mdi mdi-refresh"></i> Scan Lagi
                </button>
            </div>
        </div>

    </div>
</div>

{{-- Audio beep --}}
<audio id="beep-sound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>
@endsection

@push('styles')
<style>
    #reader video {
        border-radius: 8px;
    }
    #reader__scan_region {
        border-radius: 8px;
    }
</style>
@endpush

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
            { fps: 10, qrbox: { width: 300, height: 150 } },
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

        // Ambil data barang dari server
        fetch(`/barcode/cari?id_barang=${encodeURIComponent(decodedText)}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('result-id').textContent    = data.barang.id_barang;
                document.getElementById('result-nama').textContent  = data.barang.nama;
                document.getElementById('result-harga').textContent = 'Rp ' + parseInt(data.barang.harga).toLocaleString('id-ID');
                document.getElementById('card-hasil').classList.remove('d-none');
            } else {
                document.getElementById('notfound-id').textContent = decodedText;
                document.getElementById('card-notfound').classList.remove('d-none');
            }
        })
        .catch(() => {
            document.getElementById('notfound-id').textContent = decodedText;
            document.getElementById('card-notfound').classList.remove('d-none');
        });
    }

    function onScanError(error) {
        // Diabaikan, error ini normal saat kamera belum menemukan barcode
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
        // Reset input file supaya bisa upload ulang file yang sama
        document.getElementById('input-gambar').value = '';
        startScanner();
    }

    // ── Scan dari file gambar ──────────────────────────────
    document.getElementById('input-gambar').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        // Hentikan scanner kamera dulu kalau sedang aktif
        stopScanner();

        // Reset hasil sebelumnya
        document.getElementById('card-hasil').classList.add('d-none');
        document.getElementById('card-notfound').classList.add('d-none');

        const scannerSementara = new Html5Qrcode("reader");

        scannerSementara.scanFile(file, true)
            .then(decodedText => {
                scannerSementara.clear();
                isScanning = true; // set true dulu supaya onScanSuccess tidak di-skip
                onScanSuccess(decodedText);
            })
            .catch(() => {
                scannerSementara.clear();
                alert('Barcode tidak terbaca dari gambar. Coba gambar yang lebih jelas.');
                e.target.value = ''; // reset input supaya bisa upload ulang
            });
    });
</script>
@endpush