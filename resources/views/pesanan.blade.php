@extends('layouts.app')

@section('content')

<style>
    .pesanan-wrapper {
        max-width: 1100px;
        margin: 32px auto;
        padding: 0 16px;
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 24px;
        align-items: start;
    }

    .katalog-header { margin-bottom: 20px; }
    .katalog-header h3 { font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 4px; }
    .katalog-header p  { color: #718096; font-size: 13px; margin: 0; }

    .vendor-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }

    .vendor-tab {
        padding: 7px 16px;
        border-radius: 20px;
        border: 1px solid #cbd5e0;
        background: #fff;
        font-size: 13px;
        font-weight: 500;
        color: #4a5568;
        cursor: pointer;
        transition: all 0.2s;
    }
    .vendor-tab:hover  { border-color: #667eea; color: #667eea; }
    .vendor-tab.active { background: #667eea; border-color: #667eea; color: #fff; }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 12px;
    }

    .menu-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: box-shadow 0.2s;
    }
    .menu-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

    .menu-card-name  { font-size: 14px; font-weight: 600; color: #2d3748; line-height: 1.3; }
    .menu-card-harga { font-size: 13px; color: #38a169; font-weight: 600; }

    .menu-card-barcode {
        border-top: 1px dashed #e2e8f0;
        padding-top: 8px;
        text-align: center;
    }
    .menu-card-barcode img {
        width: 100%;
        height: 32px;
        object-fit: contain;
        display: block;
    }
    .menu-card-barcode small {
        font-size: 9px;
        color: #a0aec0;
        letter-spacing: 2px;
    }

    .btn-add-menu {
        background: #667eea;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 7px 10px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
        width: 100%;
    }
    .btn-add-menu:hover { background: #5a67d8; }

    .menu-empty {
        color: #a0aec0;
        font-size: 13px;
        padding: 20px 0;
        text-align: center;
        grid-column: 1/-1;
    }

    .form-section { position: sticky; top: 20px; }

    .card-section {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }
    .card-section h5 {
        font-size: 14px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f0f0f0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-label-custom {
        font-size: 12px;
        font-weight: 600;
        color: #718096;
        margin-bottom: 5px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #cbd5e0;
        font-size: 13px;
        padding: 9px 12px;
        color: #2d3748;
        transition: border-color 0.2s;
        width: 100%;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
        outline: none;
    }
    .form-control.is-invalid {
        border-color: #e53e3e !important;
        box-shadow: 0 0 0 3px rgba(229,62,62,0.12) !important;
    }

    .invalid-msg { font-size: 11px; color: #e53e3e; margin-top: 4px; display: none; }

    .table { font-size: 12px; color: #2d3748; width: 100%; }
    .table thead th {
        background: #f7fafc;
        color: #718096;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border-bottom: 2px solid #e2e8f0;
        padding: 8px 10px;
    }
    .table tbody td { padding: 8px 10px; vertical-align: middle; border-bottom: 1px solid #f0f4f8; }

    .btn-hapus-item { background: none; border: none; color: #e53e3e; cursor: pointer; font-size: 14px; padding: 0; }

    .btn-qty {
        background: #edf2f7;
        border: 1px solid #cbd5e0;
        border-radius: 4px;
        width: 24px;
        height: 24px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        line-height: 1;
        color: #2d3748;
        transition: background 0.2s;
    }
    .btn-qty:hover { background: #667eea; color: #fff; border-color: #667eea; }

    .total-box {
        background: #f0fff4;
        border: 1px solid #c6f6d5;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .total-box .total-nominal { color: #38a169; font-size: 16px; }

    .btn-pesan {
        background: #38a169;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        width: 100%;
    }
    .btn-pesan:hover    { background: #2f855a; }
    .btn-pesan:disabled { background: #a0aec0; cursor: not-allowed; }

    .empty-cart { text-align: center; color: #a0aec0; font-size: 13px; padding: 16px 0; }

    /* Banner QR Terakhir */
    .banner-qr {
        display: none;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        border-radius: 10px;
        padding: 12px 18px;
        margin-bottom: 16px;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        box-shadow: 0 2px 8px rgba(102,126,234,0.3);
    }
    .banner-qr-text { font-size: 13px; font-weight: 500; }
    .banner-qr-text strong { display: block; font-size: 14px; margin-bottom: 2px; }
    .btn-lihat-qr {
        background: #fff;
        color: #667eea;
        border: none;
        border-radius: 6px;
        padding: 7px 14px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        text-decoration: none;
    }
    .btn-hapus-qr {
        background: none;
        border: none;
        color: rgba(255,255,255,0.7);
        font-size: 18px;
        cursor: pointer;
        line-height: 1;
        padding: 0;
    }
    .btn-hapus-qr:hover { color: #fff; }

    .toast-container {
        position: fixed; top: 20px; right: 20px;
        z-index: 9999; display: flex; flex-direction: column; gap: 10px;
    }
    .toast {
        display: flex; align-items: center; gap: 12px;
        padding: 14px 18px; border-radius: 10px;
        font-size: 14px; font-weight: 500; color: #fff;
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        animation: slideIn 0.3s ease; min-width: 280px;
    }
    .toast.success { background: #38a169; }
    .toast.error   { background: #e53e3e; }
    .toast.info    { background: #667eea; }
    .toast.warning { background: #d69e2e; }
    .toast-icon { font-size: 18px; }
    .toast-msg  { flex: 1; }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(60px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideOut {
        from { opacity: 1; transform: translateX(0); }
        to   { opacity: 0; transform: translateX(60px); }
    }

    @media (max-width: 768px) {
        .pesanan-wrapper { grid-template-columns: 1fr; }
        .form-section { position: static; }
    }
</style>

<div class="toast-container" id="toast-container"></div>

<div class="pesanan-wrapper">

    <div class="katalog-section">
        <div class="katalog-header">
            <h3>🍽️ Kantin</h3>
            <p>Pilih vendor dan tambahkan menu ke pesanan kamu</p>
        </div>

        {{-- Banner QR Pesanan Terakhir --}}
        <div class="banner-qr" id="banner-qr">
            <div class="banner-qr-text">
                <strong>📱 Punya pesanan aktif?</strong>
                <span id="banner-qr-label">Lihat QR Code pesanan terakhir kamu</span>
            </div>
            <a href="#" id="btn-lihat-qr" class="btn-lihat-qr" target="_blank">Lihat QR</a>
            <button class="btn-hapus-qr" onclick="hapusRiwayatQR()" title="Tutup">×</button>
        </div>

        <div class="vendor-tabs">
            @foreach($vendors as $i => $v)
                <button
                    class="vendor-tab {{ $i === 0 ? 'active' : '' }}"
                    onclick="pilihVendor({{ $v->idvendor }}, this)"
                >
                    {{ $v->nama_vendor }}
                </button>
            @endforeach
        </div>

        <div class="menu-grid" id="menu-grid">
            <div class="menu-empty">Pilih vendor untuk melihat menu</div>
        </div>
    </div>

    <div class="form-section">

        <div class="card-section">
            <h5>Info Pemesan</h5>

            <div class="mb-3">
                <label class="form-label-custom">Nama <span style="color:#e53e3e">*</span></label>
                <input type="text" class="form-control" id="nama" placeholder="Nama kamu">
                <div class="invalid-msg" id="err-nama">Nama wajib diisi</div>
            </div>

            <div class="mb-3">
                <label class="form-label-custom">Vendor <span style="color:#e53e3e">*</span></label>
                <select id="vendor" class="form-control">
                    <option value="">-- Pilih Vendor --</option>
                    @foreach($vendors as $v)
                        <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
                    @endforeach
                </select>
                <div class="invalid-msg" id="err-vendor">Vendor wajib dipilih</div>
            </div>

            <div class="mb-0">
                <label class="form-label-custom">Metode Pembayaran</label>
                <select id="metode" class="form-control">
                    <option value="1">QRIS</option>
                    <option value="2">Virtual Account</option>
                </select>
            </div>
        </div>

        <div class="card-section">
            <h5>Pesanan Kamu</h5>

            <div id="empty-cart" class="empty-cart">🛒 Belum ada menu dipilih</div>

            <table class="table" id="tabel" style="display:none">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <div class="total-box" id="total-box" style="display:none">
                <span>Total</span>
                <span class="total-nominal">Rp <span id="total">0</span></span>
            </div>

            <button class="btn-pesan" id="btn-pesan" onclick="simpanPesanan()">
                🛒 Pesan & Bayar
            </button>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
let daftarMenu     = [];
let total          = 0;
let activeVendorId = null;
let idpesananGlobal = null;

// ============================
// CEK RIWAYAT QR DI LOCALSTORAGE
// ============================
function cekRiwayatQR() {
    const qrUrl = localStorage.getItem('qr_pesanan_url');
    const qrId  = localStorage.getItem('qr_pesanan_id');

    if (qrUrl && qrId) {
        document.getElementById('banner-qr').style.display = 'flex';
        document.getElementById('btn-lihat-qr').href = qrUrl;
        document.getElementById('banner-qr-label').textContent =
            'Pesanan #' + qrId + ' — Tap untuk lihat QR Code';
    }
}

function hapusRiwayatQR() {
    localStorage.removeItem('qr_pesanan_url');
    localStorage.removeItem('qr_pesanan_id');
    document.getElementById('banner-qr').style.display = 'none';
}

// ============================
// TOAST
// ============================
function showToast(message, type = 'info', duration = 3000) {
    const icons = { success: '✅', error: '❌', info: 'ℹ️', warning: '⚠️' };
    const toast = $(`
        <div class="toast ${type}">
            <span class="toast-icon">${icons[type]}</span>
            <span class="toast-msg">${message}</span>
        </div>
    `);
    $('#toast-container').append(toast);
    setTimeout(() => {
        toast.css('animation', 'slideOut 0.3s ease forwards');
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// ============================
// LOAD VENDOR PERTAMA OTOMATIS
// ============================
$(document).ready(function () {
    // Cek riwayat QR saat halaman dibuka
    cekRiwayatQR();

    let firstId = {{ $vendors->first() ? $vendors->first()->idvendor : 'null' }};
    if (firstId) loadMenuGrid(firstId);

    $('#vendor').change(function () {
        let id = $(this).val();
        if (id) {
            loadMenuGrid(id);
            $('.vendor-tab').removeClass('active');
        }
        clearError('vendor');
    });
});

// ============================
// KLIK TAB VENDOR
// ============================
function pilihVendor(id, el) {
    $('.vendor-tab').removeClass('active');
    $(el).addClass('active');
    activeVendorId = id;
    $('#vendor').val(id);
    clearError('vendor');
    loadMenuGrid(id);
}

// ============================
// LOAD MENU KE GRID (dengan barcode)
// ============================
function loadMenuGrid(id) {
    activeVendorId = id;
    $('#menu-grid').html('<div class="menu-empty">Memuat menu...</div>');

    $.get('/pesanan/get-menu/' + id, function (data) {
        if (data.length === 0) {
            $('#menu-grid').html('<div class="menu-empty">Menu tidak tersedia</div>');
            return;
        }

        let html = '';
        data.forEach(m => {
            let gambar = m.path_gambar
                ? `/storage/${m.path_gambar}`
                : `/assets/images/no-image.png`;

            let barcodeHtml = m.barcodeBase64
                ? `<div class="menu-card-barcode">
                       <img src="data:image/png;base64,${m.barcodeBase64}" alt="Barcode ${m.idmenu}">
                       <small>${m.idmenu}</small>
                   </div>`
                : '';

            html += `
                <div class="menu-card">
                    <img src="${gambar}" alt="${m.nama_menu}"
                         style="width:100%; height:120px; object-fit:cover; border-radius:8px;">
                    <div>
                        <div class="menu-card-name">${m.nama_menu}</div>
                        <div class="menu-card-harga">Rp ${new Intl.NumberFormat('id-ID').format(m.harga)}</div>
                    </div>
                    ${barcodeHtml}
                    <button class="btn-add-menu" onclick="tambahDariKartu(${m.idmenu}, '${m.nama_menu}', ${m.harga})">
                        + Tambah
                    </button>
                </div>
            `;
        });

        $('#menu-grid').html(html);
    }).fail(function () {
        $('#menu-grid').html('<div class="menu-empty">Gagal memuat menu</div>');
    });
}

// ============================
// TAMBAH DARI KARTU MENU
// ============================
function tambahDariKartu(idmenu, nama_menu, harga) {
    let valid = true;
    if ($('#nama').val().trim() === '') { showError('nama');   valid = false; }
    if (!$('#vendor').val())            { showError('vendor'); valid = false; }
    if (!valid) return;

    let existing = daftarMenu.findIndex(m => m.idmenu === idmenu);
    if (existing !== -1) {
        daftarMenu[existing].jumlah++;
        daftarMenu[existing].subtotal = daftarMenu[existing].jumlah * harga;
        showToast(nama_menu + ' ditambahkan', 'success', 1500);
        renderTabel();
        return;
    }

    daftarMenu.push({ idmenu, nama_menu, jumlah: 1, harga, subtotal: harga });
    showToast(nama_menu + ' ditambahkan ke keranjang', 'success', 1500);
    renderTabel();
}

// ============================
// RENDER TABEL PESANAN
// ============================
function renderTabel() {
    total = 0;
    let tbody = '';

    daftarMenu.forEach((m, i) => {
        total += m.subtotal;
        tbody += `
            <tr>
                <td>${m.nama_menu}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:6px;">
                        <button class="btn-qty" onclick="ubahQty(${i}, -1)">−</button>
                        <span>${m.jumlah}</span>
                        <button class="btn-qty" onclick="ubahQty(${i}, 1)">+</button>
                    </div>
                </td>
                <td>Rp ${new Intl.NumberFormat('id-ID').format(m.subtotal)}</td>
                <td><button class="btn-hapus-item" onclick="hapusItem(${i})">✕</button></td>
            </tr>
        `;
    });

    $('#tabel tbody').html(tbody);
    $('#total').text(new Intl.NumberFormat('id-ID').format(total));

    if (daftarMenu.length === 0) {
        $('#tabel').hide(); $('#total-box').hide(); $('#empty-cart').show();
    } else {
        $('#empty-cart').hide(); $('#tabel').show(); $('#total-box').show();
    }
}

// ============================
// UBAH QTY
// ============================
function ubahQty(index, delta) {
    daftarMenu[index].jumlah += delta;
    if (daftarMenu[index].jumlah <= 0) {
        daftarMenu.splice(index, 1);
    } else {
        daftarMenu[index].subtotal = daftarMenu[index].jumlah * daftarMenu[index].harga;
    }
    renderTabel();
}

// ============================
// HAPUS ITEM
// ============================
function hapusItem(index) {
    daftarMenu.splice(index, 1);
    renderTabel();
}

// ============================
// VALIDASI HELPER
// ============================
function showError(field)  { $('#' + field).addClass('is-invalid'); $('#err-' + field).show(); }
function clearError(field) { $('#' + field).removeClass('is-invalid'); $('#err-' + field).hide(); }
$('#nama').on('input', function () { clearError('nama'); });

// ============================
// SIMPAN + MIDTRANS
// ============================
function simpanPesanan() {
    let valid = true;
    if ($('#nama').val().trim() === '') { showError('nama');   valid = false; }
    if (!$('#vendor').val())            { showError('vendor'); valid = false; }
    if (!valid) return;

    if (daftarMenu.length === 0) {
        showToast('Belum ada menu dipilih!', 'warning');
        return;
    }

    $('#btn-pesan').prop('disabled', true).text('⏳ Memproses...');
    showToast('Menyimpan pesanan...', 'info', 2000);

    $.ajax({
        url: '/pesanan/simpan',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            nama: $('#nama').val(),
            metode_bayar: $('#metode').val(),
            total: total,
            menu: daftarMenu
        }),
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status !== 'success') {
                showToast('Gagal menyimpan pesanan', 'error');
                $('#btn-pesan').prop('disabled', false).html('🛒 Pesan & Bayar');
                return;
            }

            idpesananGlobal = res.idpesanan;
            showToast('Pesanan tersimpan! Membuka pembayaran...', 'success', 2000);

            fetch('/pesanan/checkout/' + res.idpesanan)
                .then(r => r.json())
                .then(data => {
                    if (!data.snap_token) {
                        showToast('Gagal mendapatkan token pembayaran', 'error');
                        $('#btn-pesan').prop('disabled', false).html('🛒 Pesan & Bayar');
                        return;
                    }

                    $('#btn-pesan').prop('disabled', false).html('🛒 Pesan & Bayar');

                    snap.pay(data.snap_token, {
                        onSuccess: function (result) {
                            // Simpan URL QR ke localStorage sebelum redirect
                            const qrUrl = '/payment/success/' + idpesananGlobal;
                            localStorage.setItem('qr_pesanan_url', qrUrl);
                            localStorage.setItem('qr_pesanan_id', idpesananGlobal);

                            window.location.href = qrUrl;
                        },
                        onPending: function () {
                            showToast('Menunggu pembayaran...', 'warning', 3000);
                            setTimeout(() => location.reload(), 2000);
                        },
                        onError:   function () { showToast('Pembayaran gagal, silakan coba lagi', 'error'); },
                        onClose:   function () { showToast('Pembayaran dibatalkan', 'warning', 2000); }
                    });
                })
                .catch(function () {
                    showToast('Terjadi kesalahan saat checkout', 'error');
                    $('#btn-pesan').prop('disabled', false).html('🛒 Pesan & Bayar');
                });
        },
        error: function (xhr) {
            let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan';
            showToast('Error: ' + msg, 'error');
            $('#btn-pesan').prop('disabled', false).html('🛒 Pesan & Bayar');
        }
    });
}
</script>
@endpush