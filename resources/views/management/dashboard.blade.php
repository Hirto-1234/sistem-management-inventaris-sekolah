@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content-admin')
<div class="p-6 font-utama">

    <h1 class="text-2xl font-bold text-utama flex items-center gap-3 mb-5">
        <i class="fa-solid fa-box-archive"></i>
        Dashboard
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

        <div class="bg-utama p-6 rounded-lg shadow-md hover:scale-105 transition-transform flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-kedua">Total Barang</p>
                <p class="text-3xl font-bold mt-2 text-kedua">{{ $total_barang }}</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-kedua rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-box text-utama text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-utama p-6 rounded-lg shadow-md hover:scale-105 transition-transform flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-kedua">Sedang Dipinjam</p>
                <p class="text-3xl font-bold mt-2 text-kedua">{{ $total_peminjaman }}</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-kedua rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-hand-holding text-utama text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-utama p-6 rounded-lg shadow-md hover:scale-105 transition-transform flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-kedua">Dalam Perbaikan</p>
                <p class="text-3xl font-bold mt-2 text-kedua">{{ $total_perbaikan }}</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-kedua rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-wrench text-utama text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-utama p-6 rounded-lg shadow-md hover:scale-105 transition-transform flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-kedua">Total Stok Inventaris</p>
                <p class="text-3xl font-bold mt-2 text-kedua">{{ $stok_inventaris }}</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-kedua rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-warehouse text-utama text-3xl"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-utama p-6 rounded-lg shadow-md hover:scale-105 transition-transform flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-kedua">Barang Masuk <br> (Bulan Ini)</p>
                <p class="text-3xl font-bold mt-2 text-kedua">{{ $barang_masuk_bulan_ini }}</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-kedua rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-arrow-down text-utama text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-utama p-6 rounded-lg shadow-md hover:scale-105 transition-transform flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-kedua">Barang Keluar <br> (Bulan Ini)</p>
                <p class="text-3xl font-bold mt-2 text-kedua">{{ $barang_keluar_bulan_ini }}</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-kedua rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-arrow-up text-utama text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-utama p-6 rounded-lg shadow-md hover:scale-105 transition-transform flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-kedua">Total Ruangan</p>
                <p class="text-3xl font-bold mt-2 text-kedua">{{ $total_ruangan }}</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-kedua rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-door-open text-utama text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-utama p-6 rounded-lg shadow-md hover:scale-105 transition-transform flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-kedua">Total Kategori</p>
                <p class="text-3xl font-bold mt-2 text-kedua">{{ $total_kategori }}</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-kedua rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-tags text-utama text-3xl"></i>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection