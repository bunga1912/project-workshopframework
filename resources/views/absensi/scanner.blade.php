@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card mt-4">
            <div class="card-body text-center py-5">

                <i class="mdi mdi-nfc" style="font-size: 64px; color: #6c63ff;"></i>
                <h4 class="mt-3 mb-1 font-weight-bold">Scanner Absensi NFC</h4>
                <p class="text-muted mb-4">Gunakan HP Android Chrome untuk scan kartu mahasiswa</p>

                {{-- STATUS --}}
                <div id="status-box" class="alert alert-secondary py-2" role="alert">
                    <i class="mdi mdi-information-outline"></i>
                    <span id="status-text">Tekan tombol untuk mengaktifkan NFC</span>
                </div>

                {{-- TOMBOL SCAN --}}
                <button id="btn-scan" class="btn btn-primary btn-lg px-5 mt-2" onclick="startScan()">
                    <i class="mdi mdi-nfc-tap"></i> Aktifkan NFC
                </button>

                {{-- HASIL SCAN --}}
                <div id="hasil" class="mt-4"></div>

            </div>
        </div>

        {{-- RIWAYAT SCAN HARI INI --}}
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="mdi mdi-history"></i> Riwayat scan hari ini</span>
                <button class="btn btn-sm btn-outline-secondary" onclick="clearRiwayat()">Hapus</button>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush" id="riwayat-list">
                    <li class="list-group-item text-muted text-center py-3" id="riwayat-kosong">
                        Belum ada scan
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
const riwayat = [];

async function startScan() {
    // Cek dukungan browser
    if (!('NDEFReader' in window)) {
        setStatus('danger', 'mdi-alert-circle', 'Browser tidak mendukung Web NFC. Gunakan Android Chrome ≥ 89.');
        return;
    }

    try {
        const ndef = new NDEFReader();
        await ndef.scan();

        setStatus('info', 'mdi-nfc-search-variant', 'NFC aktif — dekatkan kartu mahasiswa...');
        document.getElementById('btn-scan').disabled = true;
        document.getElementById('btn-scan').innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Menunggu kartu...';

        ndef.addEventListener('reading', async ({ serialNumber, message }) => {
            // Baca isi record jika ada
            let isiRecord = '';
            for (const record of message.records) {
                try { isiRecord += new TextDecoder().decode(record.data); } catch (e) {}
            }

            // Kirim ke backend Laravel
            try {
                const res = await fetch('{{ route("absensi.scan") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ serial_number: serialNumber })
                });

                const data = await res.json();

                if (data.status === 'ok') {
                    setStatus('success', 'mdi-check-circle', `✅ Absen berhasil: <strong>${data.mahasiswa}</strong> (${data.nim}) — ${data.waktu} [${data.keterangan}]`);
                    tampilHasil('success', data);
                    tambahRiwayat(data, 'success');
                } else if (data.status === 'warning') {
                    setStatus('warning', 'mdi-alert', `⚠️ ${data.message}`);
                    tampilHasil('warning', data);
                    tambahRiwayat(data, 'warning');
                } else {
                    setStatus('danger', 'mdi-alert-circle', `❌ ${data.message}`);
                    tampilHasil('danger', data);
                }
            } catch (e) {
                setStatus('danger', 'mdi-wifi-off', 'Gagal terhubung ke server. Periksa koneksi.');
            }

            // Reset tombol setelah 3 detik
            setTimeout(() => {
                document.getElementById('btn-scan').disabled = false;
                document.getElementById('btn-scan').innerHTML = '<i class="mdi mdi-nfc-tap"></i> Scan lagi';
                setStatus('info', 'mdi-nfc-search-variant', 'NFC aktif — dekatkan kartu mahasiswa...');
            }, 3000);
        });

        ndef.addEventListener('readingerror', () => {
            setStatus('danger', 'mdi-alert', 'Gagal membaca kartu. Coba lagi.');
        });

    } catch (err) {
        setStatus('danger', 'mdi-alert-circle', 'Error: ' + err.message);
        document.getElementById('btn-scan').disabled = false;
        document.getElementById('btn-scan').innerHTML = '<i class="mdi mdi-nfc-tap"></i> Aktifkan NFC';
    }
}

function setStatus(type, icon, msg) {
    const box = document.getElementById('status-box');
    box.className = `alert alert-${type} py-2`;
    box.innerHTML = `<i class="mdi ${icon}"></i> ${msg}`;
}

function tampilHasil(type, data) {
    const el = document.getElementById('hasil');
    if (data.status === 'ok') {
        el.innerHTML = `
            <div class="card border-${type}">
                <div class="card-body py-3">
                    <h5 class="font-weight-bold mb-1">${data.mahasiswa}</h5>
                    <p class="mb-1 text-muted">${data.nim}</p>
                    <span class="badge badge-${type}">${data.keterangan?.toUpperCase()}</span>
                    <span class="ml-2 text-muted">${data.waktu}</span>
                </div>
            </div>`;
    } else {
        el.innerHTML = `<div class="alert alert-${type}">${data.message}</div>`;
    }
}

function tambahRiwayat(data, type) {
    const list = document.getElementById('riwayat-list');
    document.getElementById('riwayat-kosong')?.remove();

    const item = document.createElement('li');
    item.className = 'list-group-item d-flex justify-content-between align-items-center';
    item.innerHTML = `
        <div>
            <strong>${data.mahasiswa ?? '—'}</strong>
            <small class="text-muted ml-2">${data.nim ?? ''}</small>
        </div>
        <div>
            <span class="badge badge-${type}">${data.keterangan ?? data.status}</span>
            <small class="text-muted ml-1">${data.waktu ?? ''}</small>
        </div>`;
    list.prepend(item);
}

function clearRiwayat() {
    const list = document.getElementById('riwayat-list');
    list.innerHTML = '<li class="list-group-item text-muted text-center py-3" id="riwayat-kosong">Belum ada scan</li>';
}
</script>
@endpush