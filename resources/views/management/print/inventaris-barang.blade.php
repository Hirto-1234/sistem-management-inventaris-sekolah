<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Inventaris Barang</title>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --font-utama: 'Lexend', sans-serif;
            --warna-utama: #1E3A8A;
            --warna-kedua: #FFFFFF;
            --warna-pendukung-1: #FF4D4D;
            --warna-pendukung-2: #FFD700;
            --warna-pendukung-3: #00C853;
            --warna-pendukung-4: #1E88E5;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-utama);
            font-size: 11px;
            color: var(--warna-utama);
            padding: 30px;
            background: var(--warna-kedua);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid var(--warna-utama);
        }

        .company-info h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--warna-utama);
            margin-bottom: 8px;
        }

        .company-info p {
            font-size: 10px;
            color: var(--warna-utama);
            line-height: 1.8;
        }

        .company-info p i {
            width: 16px;
            color: var(--warna-utama);
            margin-right: 5px;
        }

        .document-info {
            text-align: right;
        }

        .document-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--warna-utama);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .document-title i {
            margin-right: 8px;
        }

        .document-date {
            font-size: 10px;
            color: var(--warna-utama);
        }

        .info-section {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0;
            margin-bottom: 30px;
            background: var(--warna-utama);
            padding: 0;
            border-radius: 0;
            border: none;
        }

        .info-item {
            display: flex;
            font-size: 10px;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .info-item:nth-child(odd) {
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }

        .info-item:nth-last-child(-n+2) {
            border-bottom: none;
        }

        .info-label {
            min-width: 120px;
            font-weight: 500;
            color: var(--warna-kedua);
        }

        .info-label i {
            margin-right: 8px;
            width: 16px;
        }

        .info-value {
            font-weight: 600;
            color: var(--warna-kedua);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        thead {
            background: var(--warna-utama);
            color: var(--warna-kedua);
        }

        th {
            padding: 12px 10px;
            text-align: left;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        th.center { text-align: center; }

        tbody tr {
            border-bottom: 1px solid #e0e0e0;
        }

        tbody tr:hover {
            background: #f0f9f9;
        }

        td {
            padding: 12px 10px;
            font-size: 10px;
            color: var(--warna-utama);
        }

        td.center { text-align: center; }
        td.bold { font-weight: 600; color: var(--warna-utama); }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            background: var(--warna-utama);
            color: var(--warna-kedua);
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
        }

        .kategori-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
            background: #e8f5e9;
            color: #4caf50;
        }

        tfoot {
            background: #f0f9f9;
            border-top: 3px solid var(--warna-utama);
        }

        tfoot td {
            padding: 14px 10px;
            font-weight: 700;
            font-size: 11px;
            color: var(--warna-utama);
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }

        .signature-title {
            font-size: 10px;
            color: var(--warna-utama);
            margin-bottom: 60px;
        }

        .signature-name {
            font-size: 11px;
            font-weight: 700;
            color: var(--warna-utama);
            padding-top: 10px;
            border-top: 2px solid var(--warna-utama);
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--warna-utama);
            text-align: center;
            font-size: 9px;
            color: var(--warna-utama);
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: var(--warna-utama);
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="company-info">
            <h1>One Inven</h1>
            <p>
                <i class="fas fa-map-marker-alt"></i>Bekasi<br>
                <i class="fas fa-phone"></i>08123456789<br>
                <i class="fas fa-envelope"></i>oneinven@gmail.com
            </p>
        </div>
        <div class="document-info">
            <div class="document-title"><i class="fas fa-boxes"></i>LAPORAN INVENTARIS BARANG</div>
            <div class="document-date">{{ \Carbon\Carbon::now()->format('d F Y') }}</div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-item">
            <div class="info-label"><i class="fas fa-print"></i>Tanggal Cetak</div>
            <div class="info-value" id="tanggal-cetak">{{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label"><i class="fas fa-user"></i>Dicetak Oleh</div>
            <div class="info-value">{{ Auth::user()->nama_lengkap ?? Auth::user()->nama_pengguna }}</div>
        </div>
        
        {{-- INFORMASI FILTER PENCARIAN --}}
        <div class="info-item">
            <div class="info-label"><i class="fas fa-search"></i>Pencarian</div>
            <div class="info-value">
                {{ request('search') ? request('search') : 'Semua Barang' }}
            </div>
        </div>
        
        {{-- INFORMASI FILTER KATEGORI --}}
        <div class="info-item">
            <div class="info-label"><i class="fas fa-layer-group"></i>Kategori</div>
            <div class="info-value">
                @if(request('kategori'))
                    @php
                        $kategori = \App\Models\Kategori::find(request('kategori'));
                    @endphp
                    {{ $kategori ? $kategori->nama_kategori : 'Semua Kategori' }}
                @else
                    Semua Kategori
                @endif
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="center">No</th>
                <th width="12%"><i class="fas fa-qrcode"></i> Kode QR</th>
                <th width="12%"><i class="fas fa-barcode"></i> SKU</th>
                <th width="25%"><i class="fas fa-box"></i> Nama Barang</th>
                <th width="15%"><i class="fas fa-tag"></i> Kategori</th>
                <th width="10%" class="center"><i class="fas fa-cubes"></i> Stok</th>
                <th width="10%" class="center"><i class="fas fa-layer-group"></i> Jumlah</th>
                <th width="11%"><i class="fas fa-align-left"></i> Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangs as $index => $barang)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td><span class="badge">{{ $barang->kode_qr ?? '-' }}</span></td>
                <td><span class="badge">{{ $barang->sku_barang ?? '-' }}</span></td>
                <td class="bold">{{ $barang->nama_barang }}</td>
                <td class="center">
                    <span class="kategori-badge">{{ $barang->kategori->nama_kategori ?? '-' }}</span>
                </td>
                <td class="center bold">{{ $barang->stok_barang ?? 0 }}</td>
                <td class="center bold">{{ $barang->jumlah_barang ?? 0 }}</td>
                <td>{{ Str::limit($barang->deskripsi_barang ?? '-', 30) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="empty"><i class="fas fa-inbox"></i> Tidak ada data barang</td>
            </tr>
            @endforelse
        </tbody>

        @if($barangs->count() > 0)
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right;"><i class="fas fa-calculator"></i> TOTAL</td>
                <td class="center">{{ $barangs->sum('stok_barang') }}</td>
                <td class="center">{{ $barangs->sum('jumlah_barang') }}</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="signature">
        <div class="signature-box">
            <div class="signature-title">
                <i class="fas fa-user-tie"></i>
                @if(Auth::user()->role_pengguna == 'admin')
                    Administrator
                @elseif(Auth::user()->role_pengguna == 'petugas')
                    Petugas Gudang
                @else
                    Kepala Gudang
                @endif
            </div>
            <div class="signature-name">{{ Auth::user()->nama_lengkap ?? Auth::user()->nama_pengguna }}</div>
        </div>
    </div>

    <div class="footer">
        <i class="fas fa-print"></i> Dicetak pada <span id="footer-cetak">{{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</span>
    </div>

    <script>
        function updateWaktuCetak() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hour = String(now.getHours()).padStart(2, '0');
            const minute = String(now.getMinutes()).padStart(2, '0');
            const second = String(now.getSeconds()).padStart(2, '0');

            const timestamp = `${year}-${month}-${day} ${hour}:${minute}:${second}`;

            const tanggalCetakEl = document.getElementById('tanggal-cetak');
            const footerCetakEl = document.getElementById('footer-cetak');

            if (tanggalCetakEl) tanggalCetakEl.textContent = timestamp;
            if (footerCetakEl) footerCetakEl.textContent = timestamp;
        }

        window.addEventListener('load', updateWaktuCetak);
    </script>

</body>
</html>