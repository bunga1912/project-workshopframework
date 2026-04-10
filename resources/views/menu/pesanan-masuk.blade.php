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

    .pesanan-id {
        font-size: 13px;
        font-weight: 700;
        color: #2d3748;
    }

    .pesanan-nama {
        font-size: 13px;
        color: #4a5568;
        margin-top: 2px;
    }

    .pesanan-waktu {
        font-size: 12px;
        color: #a0aec0;
        margin-top: 2px;
    }

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

    .pesanan-card-body {
        padding: 16px 20px;
    }

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

    .pesanan-metode {
        font-size: 12px;
        color: #718096;
        text-transform: uppercase;
        font-weight: 500;
    }

    .pesanan-total {
        font-size: 15px;
        font-weight: 700;
        color: #38a169;
    }

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

    <div class="pm-header">
        <h3>📋 Pesanan Masuk</h3>
        <p>Daftar pesanan customer untuk vendor kamu</p>
    </div>

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

@endsection