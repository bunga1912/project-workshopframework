<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Antrian #{{ $nomor }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .tiket-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .tiket-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
        }

        .tiket-header {
            background: #e74c3c;
            color: #fff;
            text-align: center;
            padding: 28px 24px 20px;
        }

        .tiket-header h2 {
            font-size: 1rem;
            font-weight: 500;
            letter-spacing: 3px;
            text-transform: uppercase;
            opacity: 0.9;
            margin-bottom: 4px;
        }

        .tiket-header p {
            font-size: 0.85rem;
            opacity: 0.75;
        }

        .tiket-body {
            padding: 32px 28px;
            text-align: center;
        }

        .label-nomor {
            font-size: 0.75rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 8px;
        }

        .nomor-besar {
            font-size: 7rem;
            font-weight: 800;
            color: #e74c3c;
            line-height: 1;
            margin-bottom: 16px;
        }

        .nama-pelanggan {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 6px;
        }

        .info-text {
            font-size: 0.9rem;
            color: #888;
            line-height: 1.6;
        }

        .divider {
            border: none;
            border-top: 2px dashed #eee;
            margin: 24px 0;
        }

        .status-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-align: left;
        }

        .status-icon { font-size: 1.8rem; }

        .status-label {
            font-size: 0.75rem;
            color: #aaa;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-value {
            font-size: 1rem;
            font-weight: 600;
            color: #27ae60;
        }

        .tiket-footer {
            background: #fafafa;
            border-top: 1px solid #eee;
            padding: 16px 28px;
            text-align: center;
        }

        .tiket-footer p {
            font-size: 0.8rem;
            color: #aaa;
        }

        .tiket-footer strong {
            color: #e74c3c;
        }

        .btn-papan {
            display: block;
            margin: 20px auto 0;
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            width: 100%;
            text-align: center;
        }

        .btn-papan:hover {
            background: #c0392b;
            color: #fff;
            text-decoration: none;
        }

        .watermark {
            text-align: center;
            margin-top: 16px;
            font-size: 0.75rem;
            color: #bbb;
        }
    </style>
</head>
<body>
    <div class="tiket-wrapper">
        <div class="tiket-card">
            <div class="tiket-header">
                <h2>🍽️ Kantin</h2>
                <p>Tunjukkan tiket ini saat namamu dipanggil</p>
            </div>

            <div class="tiket-body">
                <div class="label-nomor">Nomor Antrian</div>
                <div class="nomor-besar">{{ $nomor }}</div>
                <div class="nama-pelanggan">{{ $nama }}</div>
                <div class="info-text">Pesananmu sedang disiapkan.<br>Tunggu hingga nomormu dipanggil.</div>

                <hr class="divider">

                <div class="status-box">
                    <div class="status-icon">⏳</div>
                    <div>
                        <div class="status-label">Status</div>
                        <div class="status-value" id="status-tiket">Menunggu dipanggil</div>
                    </div>
                </div>

                <a href="{{ route('antrian.papan') }}" target="_blank" class="btn-papan">
                    📺 Lihat Papan Antrian
                </a>
            </div>

            <div class="tiket-footer">
                <p>Dikeluarkan pada <strong>{{ now()->format('H:i') }}</strong> — {{ now()->format('d M Y') }}</p>
            </div>
        </div>

        <div class="watermark">Sistem Antrian Digital Kantin</div>
    </div>

    <script>
        // SSE untuk update status tiket secara real-time
        const nomorSaya = {{ $nomor }};
        const source = new EventSource('{{ route("sse.antrian") }}');

        source.addEventListener('queue-update', function (e) {
            const data = JSON.parse(e.data);
            const elStatus = document.getElementById('status-tiket');

            if (data.dipanggil && data.dipanggil.nomor === nomorSaya) {
                elStatus.textContent = '🔔 Kamu dipanggil! Segera ambil pesananmu.';
                elStatus.style.color = '#e74c3c';
                document.title      = `🔔 DIPANGGIL! No. ${nomorSaya}`;
            } else {
                const posisi = data.menunggu.findIndex(a => a.nomor === nomorSaya);
                if (posisi >= 0) {
                    elStatus.textContent = `Antrian ke-${posisi + 1} dari ${data.menunggu.length}`;
                    elStatus.style.color = '#27ae60';
                } else if (!data.dipanggil || data.dipanggil.nomor !== nomorSaya) {
                    // Sudah selesai / tidak ada di list
                    elStatus.textContent = 'Selesai';
                    elStatus.style.color = '#888';
                    source.close();
                }
            }
        });
    </script>
</body>
</html>