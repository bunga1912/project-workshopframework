@extends('layouts.app')

@section('content')

<div class="container">

    <h3>Pemesanan Kantin</h3>

    <div class="card p-3 mb-3">

        {{-- NAMA --}}
        <div class="mb-2">
            <label>Nama</label>
            <input type="text" class="form-control" id="nama">
        </div>

        {{-- VENDOR --}}
        <div class="mb-2">
            <label>Pilih Vendor</label>
            <select id="vendor" class="form-control">
                <option value="">-- Pilih Vendor --</option>
                @foreach($vendors as $v)
                    <option value="{{ $v->idvendor }}">
                        {{ $v->nama_vendor }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- MENU --}}
        <div class="mb-2">
            <label>Pilih Menu</label>
            <select id="menu" class="form-control">
                <option value="">-- Pilih Vendor Dulu --</option>
            </select>
        </div>

        {{-- JUMLAH --}}
        <div class="mb-2">
            <label>Jumlah</label>
            <input type="number" id="jumlah" class="form-control" value="1">
        </div>

        <button class="btn btn-primary" onclick="tambahMenu()">Tambah</button>
    </div>

    {{-- LIST PESANAN --}}
    <div class="card p-3">
        <h5>Daftar Pesanan</h5>

        <table class="table table-bordered" id="tabel">
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <h5>Total: Rp <span id="total">0</span></h5>

        {{-- METODE --}}
        <div class="mb-3">
            <label>Metode Bayar</label>
            <select id="metode" class="form-control">
                <option value="qris">QRIS</option>
                <option value="va">Virtual Account</option>
            </select>
        </div>

        <button class="btn btn-success" onclick="simpanPesanan()">
            Pesan & Bayar
        </button>
    </div>

</div>

@endsection


@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- MIDTRANS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
</script>

<script>
let daftarMenu = [];
let total = 0;

// ============================
// LOAD MENU BY VENDOR
// ============================
$('#vendor').change(function(){

    let id = $(this).val();

    $('#menu').html('<option>Loading...</option>');

    if(!id){
        $('#menu').html('<option value="">-- Pilih Vendor Dulu --</option>');
        return;
    }

    $.get('/pesanan/get-menu/' + id, function(data){

        let html = '<option value="">-- Pilih Menu --</option>';

        if(data.length === 0){
            html = '<option value="">Menu tidak tersedia</option>';
        }

        data.forEach(m => {
            html += `
                <option value="${m.idmenu}" data-harga="${m.harga}">
                    ${m.nama_menu} - Rp ${new Intl.NumberFormat('id-ID').format(m.harga)}
                </option>
            `;
        });

        $('#menu').html(html);
    })
    .fail(function(){
        alert('Gagal ambil menu');
    });

});


// ============================
// TAMBAH MENU
// ============================
function tambahMenu(){

    let menu = $('#menu option:selected');

    if(!menu.val()){
        alert('Pilih menu dulu!');
        return;
    }

    let jumlah = parseInt($('#jumlah').val());
    let harga = parseInt(menu.data('harga'));
    let nama = menu.text();
    let subtotal = harga * jumlah;

    total += subtotal;

    daftarMenu.push({
        idmenu: menu.val(),
        jumlah: jumlah,
        harga: harga,
        subtotal: subtotal
    });

    $('#tabel tbody').append(`
        <tr>
            <td>${nama}</td>
            <td>${jumlah}</td>
            <td>${harga}</td>
            <td>${subtotal}</td>
        </tr>
    `);

    $('#total').text(new Intl.NumberFormat('id-ID').format(total));
    $('#jumlah').val(1);
}


// ============================
// SIMPAN + MIDTRANS
// ============================
function simpanPesanan(){

    if($('#nama').val() == ''){
        alert('Nama harus diisi!');
        return;
    }

    if(daftarMenu.length == 0){
        alert('Belum ada menu!');
        return;
    }

    $.ajax({
        url: '/pesanan/simpan', // 🔥 FIX ROUTE
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            nama: $('#nama').val(),
            metode_bayar: $('#metode').val(),
            total: total,
            menu: daftarMenu
        },
        success: function(res){

            fetch('/pesanan/checkout/' + res.idpesanan)
                .then(r => r.json())
                .then(data => {

                    snap.pay(data.snap_token, {

                        onSuccess: function(){
                            alert("Pembayaran berhasil!");
                            location.reload();
                        },

                        onPending: function(){
                            alert("Menunggu pembayaran!");
                        },

                        onError: function(){
                            alert("Pembayaran gagal!");
                        }

                    });

                });

        }
    });

}
</script>

@endsection