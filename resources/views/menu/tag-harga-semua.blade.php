<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #fff; padding: 15px; }

        .grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .tag {
            width: 200px;
            border: 2px solid #2d2d2d;
            border-radius: 8px;
            overflow: hidden;
            page-break-inside: avoid;
        }

        .tag-header {
            background: #e74c3c;
            color: #fff;
            text-align: center;
            padding: 4px 6px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .tag-body {
            padding: 7px 8px;
            text-align: center;
        }

        .nama-menu {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .harga {
            font-size: 13px;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 6px;
        }

        .divider {
            border: none;
            border-top: 1px dashed #ccc;
            margin: 5px 0;
        }

        .barcode-wrapper {
            text-align: center;
            margin-bottom: 2px;
        }

        .barcode-wrapper img {
            width: 165px;
            height: 42px;
        }

        .idmenu-text {
            font-size: 7px;
            color: #aaa;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>
    <div class="grid">
        @foreach($menu as $menu)
        <div class="tag">
            <div class="tag-header">
                {{ $menu->nama_vendor ?? ($menu->vendor->nama_vendor ?? 'Workshop') }}
            </div>
            <div class="tag-body">
                <div class="nama-menu">{{ $menu->nama_menu }}</div>
                <div class="harga">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>

                <hr class="divider">

                {{-- BARCODE di atas nomor idmenu --}}
                <div class="barcode-wrapper">
                    <img src="data:image/png;base64,{{ $menu->barcodeBase64 }}"
                         alt="Barcode {{ $menu->idmenu }}">
                </div>
                <div class="idmenu-text">{{ $menu->idmenu }}</div>
            </div>
        </div>
        @endforeach
    </div>
</body>
</html>