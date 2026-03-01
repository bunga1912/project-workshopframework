<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Barang</title>

    <style>
    @page {
        size: A4;
        margin: 10mm 8mm; /* sesuai margin kertas 108 */
    }

    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        width: 20%;        /* 5 kolom */
        height: 33mm;      /* tinggi per label kertas 108 */
        border: 1px dashed #ccc;
        text-align: center;
        vertical-align: middle;
        padding: 3px;
        box-sizing: border-box;
    }

    .nama {
        font-size: 10px;
        font-weight: bold;
    }

    .harga {
        font-size: 12px;
        margin-top: 4px;
    }

    @media print {
        td {
            border: 1px dashed #ccc;
        }
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