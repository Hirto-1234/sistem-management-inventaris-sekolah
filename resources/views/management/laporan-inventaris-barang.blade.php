@extends('layouts.admin')

@section('title', 'Laporan Inventaris Barang')

@section('content-admin')
<div class="p-6 font-utama">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-utama">
            <i class="fa-solid fa-file-lines mr-3"></i>Inventaris Barang
        </h1>

        <a href="{{ route('management.laporan.inventaris-barang.print', request()->query()) }}" target="_blank"
            class="bg-ketiga text-utama px-4 py-2 rounded-lg flex items-center hover:scale-105 transition">
            <i class="fa-solid fa-print mr-2"></i>Print PDF
        </a>
    </div>

    <form method="GET" class="mb-6 bg-utama p-4 rounded-lg border border-kedua shadow-md flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fa-solid fa-search absolute left-3 top-3 text-kedua"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang..." class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
        </div>
        <div class="w-full sm:w-48 relative">
            <i class="fa-solid fa-layer-group absolute left-3 top-3 text-kedua"></i>
            <select name="kategori" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat->id_kategori }}" {{ request('kategori') == $kat->id_kategori ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-kedua text-utama rounded-lg hover:scale-105 transition">
            <i class="fa-solid fa-filter mr-2"></i>Filter
        </button>
        <a href="{{ route('management.inventaris-barang.index') }}" class="px-4 py-2 bg-pendukung-5 text-kedua rounded-lg hover:scale-105 transition">
            <i class="fa-solid fa-rotate-right mr-2"></i>Reset
        </a>
    </form>

    <div class="overflow-x-auto border border-kedua rounded-lg shadow-md bg-utama">
        <table class="w-full text-sm text-kedua">
            <thead class="bg-ketiga border-b border-kedua">
                <tr>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap "><i class="fa-solid fa-hashtag mr-2"></i>ID</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap "><i class="fa-solid fa-image mr-2"></i>Foto</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-box mr-2"></i>Nama</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-layer-group mr-2"></i>Kategori</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-cubes mr-2"></i>Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $barang)
                <tr class="border-b border-kedua hover:bg-kedua transition">
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap">
                        <i class="fa-solid fa-hashtag mr-2"></i>{{ $barang->id_barang }}
                    </td>
                    <td class="px-2 sm:px-4 py-3">
                        @if($barang->foto_barang)
                            <img src="{{ asset('uploads/barangs/' . $barang->foto_barang) }}" alt="{{ $barang->nama_barang }}" class="w-12 h-12 object-cover rounded-lg border border-kedua shadow-sm hover:scale-[115%] transition duration-300 origin-left cursor-zoom-in z-10"  onclick="openDetail({{ $barang->id_barang }})" title="Klik untuk detail">
                        @else
                            <div class="w-12 h-12 bg-gray-200 border-2 border-dashed border-kedua rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-image text-gray-400 text-lg"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm hidden md:table-cell">
                        <div class="max-w-[150px] md:max-w-none truncate">
                            <i class="fa-solid fa-box mr-2"></i>{{ ucfirst($barang->nama_barang) }}
                        </div>
                    </td>
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap hidden md:table-cell">
                        <i class="fa-solid fa-layer-group mr-2"></i>{{ ucfirst($barang->kategori->nama_kategori) }}
                    </td>
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap hidden md:table-cell">
                        <i class="fa-solid fa-cubes mr-2"></i>{{ $barang->stok_barang }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-kedua text-xs sm:text-sm">
                        <i class="fa-solid fa-inbox mr-2"></i>Belum ada data barang
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
