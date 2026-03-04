<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Barang</title>

    <style>
    @page {
    size: 210mm 167mm;
    margin: 11.5mm 10mm;
    }

    body {
    margin: 0;
    padding: 0;
    }

    table {
    width: 100%;
    border-collapse: collapse;
    }

    td {
    width: 38mm;
    height: 18mm;
    text-align: center;
    vertical-align: middle;
    padding: 2px;
    box-sizing: border-box;
    border: 1px solid #000;
    }

    .nama {
    font-size: 7px;
    font-weight: bold;
    }

    .harga {
    font-size: 8px;
    margin-top: 1px;
    }
    @media print {
    td {
        border: none; 
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