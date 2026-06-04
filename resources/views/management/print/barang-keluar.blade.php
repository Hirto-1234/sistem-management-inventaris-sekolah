<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang Keluar</title>
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
            color: #374151;
            white-space: nowrap;
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

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: 600;
            text-transform: capitalize;
            white-space: nowrap;
        }

        .status-habis_terpakai {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status-rusak_total {
            background: #ffebee;
            color: #c62828;
        }

        .status-hilang {
            background: #fff9c4;
            color: #f57f17;
        }

        .status-kadaluarsa {
            background: #fff3e0;
            color: #e65100;
        }

        .status-aus_tidak_layak {
            background: #f5f5f5;
            color: #616161;
        }

        .status-penghapusan_aset {
            background: #f3e5f5;
            color: #7b1fa2;
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
        .foot {
            margin: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 10px 0px;
        }
        .foot p {
            font-weight: bold;
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
            <div class="document-title"><i class="fas fa-box-open"></i>LAPORAN BARANG KELUAR</div>
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
        
        {{-- INFORMASI FILTER LAPORAN --}}
        <div class="info-item">
            <div class="info-label"><i class="fas fa-filter"></i>Tipe Laporan</div>
            <div class="info-value">
                {{ ucfirst(request('tipe') ?? 'Semua') }}
            </div>
        </div>
        
        {{-- BAGIAN KANAN (DETAIL FILTER) --}}
        <div class="info-item">
            <div class="info-label"><i class="fas fa-calendar-alt"></i>Detail Filter</div>
            <div class="info-value">
                
                {{-- Jika Harian --}}
                @if(request('tipe') == 'harian' && request('harian'))
                    Tanggal: {{ \Carbon\Carbon::parse(request('harian'))->format('d F Y') }}
                
                {{-- Jika Mingguan --}}
                @elseif(request('tipe') == 'mingguan' && request('mingguan'))
                    @php
                        [$tahun, $minggu] = explode('-W', request('mingguan'));
                        $start = \Carbon\Carbon::now()->setISODate($tahun, $minggu)->startOfWeek();
                        $end = \Carbon\Carbon::now()->setISODate($tahun, $minggu)->endOfWeek();
                    @endphp
                    Minggu ke-{{ $minggu }} Tahun {{ $tahun }}
                    ({{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }})
                
                {{-- Jika Bulanan --}}
                @elseif(request('tipe') == 'bulanan' && request('bulanan'))
                    @php
                        [$tahun, $bulan] = explode('-', request('bulanan'));
                    @endphp
                    Bulan: {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->format('F Y') }}
                
                {{-- Jika Rentang Tanggal --}}
                @elseif(request('tipe') == 'rentang' && request('awal') && request('akhir'))
                    {{ \Carbon\Carbon::parse(request('awal'))->format('d/m/Y') }} - 
                    {{ \Carbon\Carbon::parse(request('akhir'))->format('d/m/Y') }}
                
                {{-- Jika Tidak Ada (default semua) --}}
                @else
                    Semua Periode
                @endif
                
                {{-- Jika ada filter kategori barang keluar --}}
                @if(request('kategori_keluar'))
                    <br>
                    Kategori: {{ ucwords(str_replace('_',' ', request('kategori_keluar'))) }}
                @endif
                
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="8%" class="center">No</th>
                <th width="10%"><i class="fas fa-barcode"></i> Kode</th>
                <th width="18%"><i class="fas fa-box"></i> Nama Barang</th>
                <th width="13%"><i class="fas fa-door-open"></i> Ruangan</th>
                <th width="13%" class="center"><i class="fas fa-calendar"></i> Tanggal</th>
                <th width="10%" class="center"><i class="fas fa-cubes"></i> Jumlah</th>
                <th width="15%" class="center"><i class="fas fa-tags"></i> Kategori</th>
                <th width="13%"><i class="fas fa-sticky-note"></i> Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangKeluar as $index => $bk)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td><span class="badge">{{ $bk->id_barang_keluar }}</span></td>
                <td class="bold">{{ $bk->inventarisRuangan->barang->nama_barang ?? '-' }}</td>
                <td>{{ $bk->inventarisRuangan->ruangan->nama_ruangan ?? '-' }}</td>
                <td class="center">{{ \Carbon\Carbon::parse($bk->tanggal_keluar)->format('d/m/Y') }}</td>
                <td class="center bold">{{ $bk->jumlah_keluar }}</td>
                <td class="center">
                    <span class="status-badge status-{{ $bk->kategori_keluar }}">
                        @if($bk->kategori_keluar == 'habis_terpakai')
                            <i class="fas fa-check-circle"></i>
                        @elseif($bk->kategori_keluar == 'rusak_total')
                            <i class="fas fa-times-circle"></i>
                        @elseif($bk->kategori_keluar == 'hilang')
                            <i class="fas fa-question-circle"></i>
                        @elseif($bk->kategori_keluar == 'kadaluarsa')
                            <i class="fas fa-clock"></i>
                        @elseif($bk->kategori_keluar == 'aus_tidak_layak')
                            <i class="fas fa-exclamation-triangle"></i>
                        @elseif($bk->kategori_keluar == 'penghapusan_aset')
                            <i class="fas fa-trash-alt"></i>
                        @endif
                        {{ str_replace('_', ' ', ucwords($bk->kategori_keluar ?? '-')) }}
                    </span>
                </td>
                <td>{{ Str::limit($bk->keterangan ?? '-', 30) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="empty"><i class="fas fa-inbox"></i> Tidak ada data barang keluar</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="foot">
        <p><i class="fas fa-calculator"></i> TOTAL BARANG KELUAR : {{ $barangKeluar->sum('jumlah_keluar') }}</p>
    </div>

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