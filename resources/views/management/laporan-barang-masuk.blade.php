@extends('layouts.admin')

@section('title', 'Laporan Barang Masuk')

@section('content-admin')
<div class="p-6 mb-6 font-utama">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-utama">
            <i class="fa-solid fa-file-lines mr-3"></i>Barang Masuk
        </h1>

        <a href="{{ route('management.laporan.barang-masuk.print', request()->query()) }}" target="_blank"
            class="bg-ketiga text-utama px-4 py-2 rounded-lg flex items-center hover:scale-105 transition">
            <i class="fa-solid fa-print mr-2"></i>Print PDF
        </a>
    </div>

    <form method="GET"
    x-data="{ tipe: '{{ request('tipe') ?? 'harian' }}' }"
    class="mb-6 bg-utama p-4 rounded-lg border border-kedua shadow-md">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
        <div>
            <label class="text-kedua font-semibold block mb-1">Tipe Laporan</label>
            <select name="tipe" x-model="tipe"
                class="w-full px-3 py-2 border border-kedua rounded-lg bg-utama text-kedua">
                <option value="harian">Harian</option>
                <option value="mingguan">Mingguan</option>
                <option value="bulanan">Bulanan</option>
                <option value="rentang">Rentang Tanggal</option>
            </select>
        </div>

        <div class="relative" x-show="tipe === 'harian'">
            <label class="text-kedua font-semibold block mb-1">Tanggal</label>
            <i class="fa-solid fa-calendar-day absolute left-3 top-10 text-kedua"></i>
            <input type="date" name="harian"
                class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
        </div>

        <div class="relative" x-show="tipe === 'mingguan'">
            <label class="text-kedua font-semibold block mb-1">Minggu</label>
            <i class="fa-solid fa-calendar-week absolute left-3 top-10 text-kedua"></i>
            <input type="week" name="mingguan"
                class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
        </div>

        <div class="relative" x-show="tipe === 'bulanan'">
            <label class="text-kedua font-semibold block mb-1">Bulan</label>
            <i class="fa-solid fa-calendar absolute left-3 top-10 text-kedua"></i>
            <input type="month" name="bulanan"
                class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
        </div>

        <div class="grid grid-cols-2 gap-4" x-show="tipe === 'rentang'">
            <div class="relative">
                <label class="text-kedua font-semibold block mb-1">Awal</label>
                <i class="fa-solid fa-arrow-right-to-bracket absolute left-3 top-10 text-kedua"></i>
                <input type="date" name="awal"
                    class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
            </div>

            <div class="relative">
                <label class="text-kedua font-semibold block mb-1">Akhir</label>
                <i class="fa-solid fa-arrow-right-from-bracket absolute left-3 top-10 text-kedua"></i>
                <input type="date" name="akhir"
                    class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
            </div>
        </div>

    </div>

    <!-- BUTTONS -->
    <div class="flex gap-2 mt-4">
        <button type="submit"
            class="px-4 py-2 bg-kedua text-utama rounded-lg hover:scale-105 transition w-full">
            <i class="fa-solid fa-filter mr-2"></i>Filter
        </button>

        <a href="{{ route('management.laporan.barang-masuk') }}"
            class="px-4 py-2 bg-gray-400 text-kedua rounded-lg hover:scale-105 transition w-full">
            <i class="fa-solid fa-rotate-right mr-2"></i>Reset
        </a>
    </div>

</form>


    {{-- TABEL LAPORAN --}}
    <div class="overflow-x-auto border border-kedua rounded-lg shadow-md bg-utama">
        <table class="w-full text-sm text-kedua">
            <thead class="bg-ketiga border-b border-kedua">
                <tr>
                    <th class="text-utama px-4 py-3 text-left whitespace-nowrap"><i class="fa-solid fa-hashtag mr-2"></i>ID</th>
                    <th class="text-utama px-4 py-3 text-left whitespace-nowrap"><i class="fa-solid fa-box mr-2"></i>Nama Barang</th>
                    <th class="text-utama px-4 py-3 text-left whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-calendar mr-2"></i>Tanggal Masuk</th>
                    <th class="text-utama px-4 py-3 text-left whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-cubes mr-2"></i>Jumlah Masuk</th>
                    <th class="text-utama px-4 py-3 text-left whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-handshake mr-2"></i>Sumber</th>
                    <th class="text-utama px-4 py-3 text-left whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-user-circle mr-2"></i>Diinput Oleh</th>
                </tr>
            </thead>

            <tbody>
                @forelse($barangs as $bm)
                <tr class="border-b border-kedua hover:bg-kedua transition">
                    <td class="px-4 py-3"><i class="fa-solid fa-hashtag mr-2"></i>{{ $bm->id_barang_masuk }}</td>

                    <td class="px-4 py-3">
                        <div class="truncate max-w-[150px] md:max-w-none"><i class="fa-solid fa-box mr-2"></i>
                            {{ ucfirst($bm->barang->nama_barang) }}
                        </div>
                    </td>

                    <td class="px-4 py-3 hidden md:table-cell"><i class="fa-solid fa-calendar mr-2"></i>
                        {{ $bm->tanggal_masuk->format('d-m-Y') }}
                    </td>

                    <td class="px-4 py-3 hidden md:table-cell"><i class="fa-solid fa-cubes mr-2"></i>
                        {{ $bm->jumlah_masuk }}
                    </td>

                    <td class="px-4 py-3 hidden md:table-cell"><i class="fa-solid fa-handshake mr-2"></i>
                        {{ $bm->sumber ?? '-' }}
                    </td>

                    <td class="px-4 py-3 hidden md:table-cell"><i class="fa-solid fa-user-circle mr-2"></i>
                        {{ $bm->pengguna->nama_pengguna }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center">
                        <i class="fa-solid fa-inbox mr-2"></i>Tidak ada data
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $barangs->links() }}
    </div>

</div>
@endsection
