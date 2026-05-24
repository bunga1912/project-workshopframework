@extends('layouts.app')

@section('content')

<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="font-weight-bold mb-1">📺 Papan Antrian</h4>
            <p class="text-muted mb-0">Tampilan antrian real-time</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span id="sse-status" class="badge badge-success">● Live</span>
            <a href="{{ route('pesanan.masuk') }}" class="btn btn-sm btn-outline-danger ml-2">
                📢 Ke Halaman Panggil
            </a>
        </div>
    </div>
</div>

{{-- Display utama --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
                                  border: none; border-radius: 20px; overflow: hidden;">
            <div class="card-body text-center py-5">
                <p style="font-size:0.8rem; letter-spacing:5px; text-transform:uppercase;
                           color:#888; margin-bottom:16px;">
                    ✦ Sedang Dilayani ✦
                </p>

                <div id="nomor-sekarang"
                     style="font-size:9rem; font-weight:900; color:#e74c3c; line-height:1;
                            text-shadow: 0 0 60px rgba(231,76,60,0.4);
                            transition: all 0.4s ease;">
                    —
                </div>

                <div id="nama-sekarang"
                     style="font-size:2rem; font-weight:500; color:#ecf0f1;
                            margin-top:16px; min-height:56px;
                            transition: all 0.4s ease;">
                    Menunggu panggilan...
                </div>

                <div id="pesan-sekarang"
                     style="color:#aaa; margin-top:10px; font-size:1rem;">
                </div>
            </div>

            {{-- Progress bar dekoratif --}}
            <div style="height: 4px; background: linear-gradient(90deg, #e74c3c, #c0392b, #e74c3c);
                         background-size: 200% 100%; animation: shimmer 2s infinite linear;">
            </div>
        </div>
    </div>
</div>

{{-- Stats row --}}
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card text-center h-100" style="border-left: 4px solid #3498db;">
            <div class="card-body py-3">
                <p class="text-muted mb-1" style="font-size:0.75rem; letter-spacing:2px; text-transform:uppercase;">
                    Menunggu
                </p>
                <div id="stat-menunggu" style="font-size:2.5rem; font-weight:800; color:#3498db; line-height:1;">
                    0
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center h-100" style="border-left: 4px solid #e74c3c;">
            <div class="card-body py-3">
                <p class="text-muted mb-1" style="font-size:0.75rem; letter-spacing:2px; text-transform:uppercase;">
                    Dipanggil
                </p>
                <div id="stat-dipanggil" style="font-size:2.5rem; font-weight:800; color:#e74c3c; line-height:1;">
                    —
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center h-100" style="border-left: 4px solid #f39c12;">
            <div class="card-body py-3">
                <p class="text-muted mb-1" style="font-size:0.75rem; letter-spacing:2px; text-transform:uppercase;">
                    Tidak Hadir
                </p>
                <div id="stat-terlambat" style="font-size:2.5rem; font-weight:800; color:#f39c12; line-height:1;">
                    0
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Antrian menunggu --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 font-weight-bold">⏳ Antrian Menunggu</h6>
                <span id="badge-menunggu" class="badge badge-primary">0</span>
            </div>
            <div class="card-body">
                <div id="waiting-items" class="d-flex flex-wrap" style="gap: 10px;">
                    <span class="text-muted">Belum ada antrian</span>
                </div>
            </div>
        </div>
    </div>
</div>

<audio id="audio-dingdong" src="{{ asset('sounds/bell canteen.mp3') }}" preload="auto"></audio>

@endsection

@push('styles')
<style>
    @keyframes shimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    @keyframes popIn {
        0%   { transform: scale(0.8); opacity: 0; }
        70%  { transform: scale(1.05); }
        100% { transform: scale(1); opacity: 1; }
    }

    .pop { animation: popIn 0.5s ease forwards; }
</style>
@endpush

@push('scripts')
<script>
    let nomorSebelumnya = null;
    let audioUnlocked   = false;

    document.addEventListener('click', function () {
        if (audioUnlocked) return;
        const audio = document.getElementById('audio-dingdong');
        audio.volume = 0;
        audio.play().then(() => {
            audio.pause();
            audio.currentTime = 0;
            audio.volume = 1;
            audioUnlocked = true;
        }).catch(() => {});
    }, { once: true });

    function updateNomor(dipanggil) {
        const elNomor = document.getElementById('nomor-sekarang');
        const elNama  = document.getElementById('nama-sekarang');
        const elPesan = document.getElementById('pesan-sekarang');
        const elStat  = document.getElementById('stat-dipanggil');

        if (!dipanggil) {
            elNomor.textContent = '—';
            elNama.textContent  = 'Menunggu panggilan...';
            elPesan.textContent = '';
            elStat.textContent  = '—';
            nomorSebelumnya = null;
            return;
        }

        elStat.textContent = dipanggil.nomor;

        if (dipanggil.nomor !== nomorSebelumnya) {
            elNomor.classList.remove('pop');
            elNama.classList.remove('pop');
            void elNomor.offsetWidth;

            elNomor.textContent = dipanggil.nomor;
            elNama.textContent  = dipanggil.nama;
            elPesan.textContent = 'Silakan ambil pesananmu 🍱';

            elNomor.classList.add('pop');
            elNama.classList.add('pop');

            if (audioUnlocked) bunyikanPanggilan(dipanggil.nomor, dipanggil.nama);

            nomorSebelumnya = dipanggil.nomor;
        }
    }

    function updateWaiting(menunggu) {
        const el    = document.getElementById('waiting-items');
        const badge = document.getElementById('badge-menunggu');
        const stat  = document.getElementById('stat-menunggu');

        badge.textContent = menunggu.length;
        stat.textContent  = menunggu.length;

        if (!menunggu || menunggu.length === 0) {
            el.innerHTML = '<span class="text-muted">Tidak ada antrian menunggu</span>';
            return;
        }

        el.innerHTML = menunggu.map((a, i) =>
            `<div style="background: #f8f9fa; border: 1px solid #e2e8f0; border-radius: 10px;
                          padding: 10px 16px; display: flex; align-items: center; gap: 10px;">
                <span style="background: #3498db; color: #fff; border-radius: 50%;
                              width: 32px; height: 32px; display: flex; align-items: center;
                              justify-content: center; font-weight: 700; font-size: 0.9rem;
                              flex-shrink: 0;">
                    ${a.nomor}
                </span>
                <div>
                    <div style="font-weight: 600; font-size: 0.9rem; color: #2d3748;">${a.nama}</div>
                    <div style="font-size: 0.75rem; color: #a0aec0;">${a.created_at}</div>
                </div>
            </div>`
        ).join('');
    }

    function updateTerlambat(terlambat) {
        document.getElementById('stat-terlambat').textContent = terlambat.length;
    }

    function bunyikanPanggilan(nomor, nama) {
        const audio = document.getElementById('audio-dingdong');
        audio.currentTime = 0;
        audio.play();
        audio.onended = function () {
            if (!('speechSynthesis' in window)) return;
            window.speechSynthesis.cancel();
            const pesan = new SpeechSynthesisUtterance(
                `Nomor antrian ${nomor}. ${nama}. Pesanan kamu sudah siap, silakan ambil.`
            );
            pesan.lang   = 'id-ID';
            pesan.rate   = 0.85;
            pesan.pitch  = 1.0;
            pesan.volume = 1.0;
            window.speechSynthesis.speak(pesan);
        };
    }

    const source = new EventSource('{{ route("sse.antrian") }}');

    source.addEventListener('queue-update', function (e) {
        const data = JSON.parse(e.data);
        updateNomor(data.dipanggil);
        updateWaiting(data.menunggu);
        updateTerlambat(data.terlambat);

        document.getElementById('sse-status').textContent = '● Live';
        document.getElementById('sse-status').className   = 'badge badge-success';
    });

    source.onerror = function () {
        document.getElementById('sse-status').textContent = '● Offline';
        document.getElementById('sse-status').className   = 'badge badge-danger';
    };
</script>
@endpush