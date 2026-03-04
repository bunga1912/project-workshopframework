<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Barang</title>

    <style>
    @page {
    margin: 0;
    }

    body {
    margin: 0;
    background-color: #fff2bf; /* warna kertas kuning */
    width: 210mm;
    height: 167mm;
    }

    /* ===== TABEL STIKER ===== */
    table {
    border-collapse: separate;
    border-spacing: 2mm 2mm; /* 0,3 cm samping | 0,2 cm bawah */
    margin: 0 auto;
    }

    td {
    width: 38mm;   /* 3,8 cm */
    height: 18mm;  /* 1,8 cm */
    background: #ffffff; /* label putih */
    border: 0.3 px solid #000; /* border per label */
    border-radius: 10px; /* lengkungan */
    text-align: center;
    vertical-align: middle;
    }

    /* ===== ISI TAG ===== */
    .nama {
    font-size: 7pt;
    font-weight: bold;
    line-height: 1.1;
    }

    .kode {
    font-size: 6pt;
    }

    .harga {
    font-size: 9pt;
    font-weight: bold;
    }

    .footer {
    font-size: 5pt;
    }
    </style>
</head>
<body>

<table>
    @foreach($grid as $row)
        <tr>
            @foreach($row as $item)
                <td>
                    @if($item)
                        <div class="nama">{{ $item->nama }}</div>
                        <div class="harga">
                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                        </div>
                    @endif
                </td>
            @endforeach
        </tr>
    @endforeach
</table>

</body>

<script>
    window.onload = function () {
        window.print();
    }
</script>

</html>