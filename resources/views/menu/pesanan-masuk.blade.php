@extends('layouts.app')

@section('content')

<style>
    .pm-wrapper {
        max-width: 960px;
        margin: 32px auto;
        padding: 0 16px;
    }

    .pm-header {
        margin-bottom: 24px;
    }

    .pm-header h3 {
        font-size: 20px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 4px;
    }

    .pm-header p {
        color: #718096;
        font-size: 13px;
        margin: 0;
    }

    /* Antrian panel */
    .antrian-panel {
        background: #1a1a2e;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .antrian-panel-left { }

    .antrian-label {
        font-size: 0.7rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #aaa;
        margin-bottom: 4px;
    }

    .antrian-nomor {
        font-size: 4rem;
        font-weight: 900;
        color: #e74c3c;
        line-height: 1;
        text-shadow: 0 0 30px rgba(231,76,60,0.3);
    }

    .antrian-nama {
        font-size: 1rem;
        color: #ecf0f1;
        margin-top: 6px;
    }

    .antrian-panel-right {
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: flex-end;
    }

    .btn-panggil {
        background: #e74c3c;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px 28px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-panggil:hover { background: #c0392b; }

    .btn-terlambat {
        background: transparent;
        color: #f39c12;
        border: 1px solid #f39c12;
        border-radius: 10px;
        padding: 8px 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-terlambat:hover { background: #f39c12; color: #fff; }

    .sse-status {
        font-size: 0.75rem;
        color: #aaa;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sse-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: #2ecc71;
        animation: blink 1.5s infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.3; }
    }

    /* Antrian menunggu strip */
    .menunggu-strip {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .menunggu-label {
        font-size: 12px;
        font-weight: 600;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 1px;
        white-space: nowrap;
    }

    .menunggu-items {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .menunggu-badge {
        background: #ebf4ff;
        color: #2b6cb0;
        border: 1px solid #bee3f8;
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 12px;
        font-weight: 600;
    }

    .pesanan-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .pesanan-card-header {
        background: #f7fafc;
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e2e8f0;
        flex-wrap: wrap;
        gap: 8px;
    }

    .pesanan-id     { font-size: 13px; font-weight: 700; color: #2d3748; }
    .pesanan-nama   { font-size: 13px; color: #4a5568; margin-top: 2px; }
    .pesanan-waktu  { font-size: 12px; color: #a0aec0; margin-top: 2px; }

    .badge-status {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .badge-pending { background: #fefcbf; color: #b7791f; }
    .badge-lunas   { background: #c6f6d5; color: #276749; }
    .badge-batal   { background: #fed7d7; color: #9b2c2c; }

    .pesanan-card-body { padding: 16px 20px; }

    .table-detail {
        width: 100%;
        font-size: 13px;
        color: #2d3748;
        border-collapse: collapse;
    }

    .table-detail thead th {
        font-size: 11px;
        font-weight: 600;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 8px 10px;
        border-bottom: 2px solid #e2e8f0;
        background: #f7fafc;
    }

    .table-detail tbody td {
        padding: 9px 10px;
        border-bottom: 1px solid #f0f4f8;
        vertical-align: middle;
    }

    .pesanan-footer {
        padding: 12px 20px;
        background: #f0fff4;
        border-top: 1px solid #c6f6d5;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pesanan-metode { font-size: 12px; color: #718096; text-transform: uppercase; font-weight: 500; }
    .pesanan-total  { font-size: 15px; font-weight: 700; color: #38a169; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #a0aec0;
        font-size: 14px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
</style>

<div class="pm-wrapper">

    <div class="pm-header d-flex justify-content-between align-items-center">
        <div>
            <h3>📋 Pesanan Masuk</h3>
            <p>Daftar pesanan customer untuk vendor kamu</p>
        </div>
        <div class="sse-status">
            <span class="sse-dot" id="sse-dot"></span>
            <span id="sse-text">Live</span>
        </div>
    </div>

    {{-- Panel antrian --}}
    <div class="antrian-panel">
        <div class="antrian-panel-left">
            <div class="antrian-label">Sedang Dilayani</div>
            <div class="antrian-nomor" id="nomor-aktif">
                {{ $dipanggil ? $dipanggil['nomor'] : '—' }}
            </div>
            <div class="antrian-nama" id="nama-aktif">
                {{ $dipanggil ? $dipanggil['nama'] : 'Belum ada panggilan' }}
            </div>
        </div>
        <div class="antrian-panel-right">
            <form action="{{ route('antrian.panggil') }}" method="POST">
                @csrf
                <button type="submit" class="btn-panggil">
                    📢 Panggil Berikutnya
                </button>
            </form>
            @if($dipanggil)
            <form action="{{ route('antrian.terlambat') }}" method="POST">
                @csrf
                <input type="hidden" name="nomor" value="{{ $dipanggil['nomor'] }}">
                <button type="submit" class="btn-terlambat">
                    ⏱ Tidak Hadir
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Strip antrian menunggu --}}
    <div class="menunggu-strip">
        <span class="menunggu-label">⏳ Menunggu</span>
        <div class="menunggu-items" id="menunggu-items">
            @if(count($menunggu) > 0)
                @foreach($menunggu as $a)
                    <span class="menunggu-badge">{{ $a['nomor'] }} — {{ $a['nama'] }}</span>
                @endforeach
            @else
                <span class="text-muted" style="font-size:13px;">Tidak ada antrian</span>
            @endif
        </div>
    </div>

    {{-- Daftar pesanan --}}
    @if($pesanan->isEmpty())
        <div class="empty-state">
            📭 Belum ada pesanan masuk
        </div>
    @else
        @foreach($pesanan as $idpesanan => $items)
            @php $first = $items->first(); @endphp

            <div class="pesanan-card">
                <div class="pesanan-card-header">
                    <div>
                        <div class="pesanan-id"># Pesanan {{ $idpesanan }}</div>
                        <div class="pesanan-nama">👤 {{ $first->nama }}</div>
                        <div class="pesanan-waktu">
                            🕐 {{ \Carbon\Carbon::parse($first->timestamp)->format('d M Y, H:i') }}
                        </div>
                    </div>
                    <div>
                        @if($first->status_bayar == 1)
                            <span class="badge-status badge-lunas">✅ Lunas</span>
                        @elseif($first->status_bayar == 0)
                            <span class="badge-status badge-pending">⏳ Pending</span>
                        @else
                            <span class="badge-status badge-batal">❌ Batal</span>
                        @endif
                    </div>
                </div>

                <div class="pesanan-card-body">
                    <table class="table-detail">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>{{ $item->nama_menu }}</td>
                                <td>{{ $item->jumlah }}x</td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                <td>{{ $item->catatan ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pesanan-footer">
                    <span class="pesanan-metode">
                        💳 {{ strtoupper($first->metode_bayar) }}
                    </span>
                    <span class="pesanan-total">
                        Total: Rp {{ number_format($first->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        @endforeach
    @endif

</div>

<audio id="audio-dingdong" src="{{ asset('sounds/bell canteen.mp3') }}" preload="auto"></audio>

@endsection

@push('scripts')
<script>
    let nomorSebelumnya = null;
    let audioUnlocked   = false;
    const csrfToken     = document.querySelector('meta[name="csrf-token"]').content;

    // Unlock audio saat klik pertama
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

    function updateMenunggu(menunggu) {
        const el = document.getElementById('menunggu-items');
        if (!menunggu || menunggu.length === 0) {
            el.innerHTML = '<span class="text-muted" style="font-size:13px;">Tidak ada antrian</span>';
            return;
        }
        el.innerHTML = menunggu.map(a =>
            `<span class="menunggu-badge">${a.nomor} — ${a.nama}</span>`
        ).join('');
    }

    const source = new EventSource('{{ route("sse.antrian") }}');

    source.addEventListener('queue-update', function (e) {
        const data = JSON.parse(e.data);

        // Update nomor aktif
        document.getElementById('nomor-aktif').textContent =
            data.dipanggil ? data.dipanggil.nomor : '—';
        document.getElementById('nama-aktif').textContent =
            data.dipanggil ? data.dipanggil.nama : 'Belum ada panggilan';

        // Bunyi kalau nomor berubah
        if (data.dipanggil && data.dipanggil.nomor !== nomorSebelumnya) {
            if (audioUnlocked) bunyikanPanggilan(data.dipanggil.nomor, data.dipanggil.nama);
            nomorSebelumnya = data.dipanggil.nomor;
        }

        updateMenunggu(data.menunggu);

        document.getElementById('sse-dot').style.background = '#2ecc71';
        document.getElementById('sse-text').textContent     = 'Live';
    });

    source.onerror = function () {
        document.getElementById('sse-dot').style.background = '#e74c3c';
        document.getElementById('sse-text').textContent     = 'Offline';
    };
</script>
@endpush