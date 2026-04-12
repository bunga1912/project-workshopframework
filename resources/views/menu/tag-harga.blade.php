<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #fff; }

        .tag {
            width: 210px;
            border: 2px solid #2d2d2d;
            border-radius: 8px;
            overflow: hidden;
        }

        .tag-header {
            background: #e74c3c;
            color: #fff;
            text-align: center;
            padding: 5px 8px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .tag-body {
            padding: 8px 10px;
            text-align: center;
        }

        .nama-menu {
            font-size: 13px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 6px;
        }

        .harga {
            font-size: 16px;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 8px;
        }

        .divider {
            border: none;
            border-top: 1px dashed #ccc;
            margin: 6px 0;
        }

        .barcode-wrapper {
            text-align: center;
            margin-bottom: 3px;
        }

        .barcode-wrapper img {
            width: 180px;
            height: 48px;
        }

        .idmenu-text {
            font-size: 8px;
            color: #aaa;
            letter-spacing: 3px;
        }
    </style>
</head>
<body>
    <div class="tag">
        <div class="tag-header">
            {{ $menu->vendor->nama_vendor ?? 'Workshop' }}
        </div>

        <div class="tag-body">
            <div class="nama-menu">{{ $menu->nama_menu }}</div>

            <div class="harga">
                Rp {{ number_format($menu->harga, 0, ',', '.') }}
            </div>

            <hr class="divider">

            {{-- BARCODE di atas nomor idmenu --}}
            <div class="barcode-wrapper">
                <img src="data:image/png;base64,{{ $barcodeBase64 }}"
                     alt="Barcode {{ $menu->idmenu }}">
            </div>
            <div class="idmenu-text">{{ $menu->idmenu }}</div>
        </div>
    </div>
</body>
</html>