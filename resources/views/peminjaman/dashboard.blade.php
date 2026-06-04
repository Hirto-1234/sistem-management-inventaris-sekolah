@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
<div class="p-6 font-utama">

    <h1 class="text-2xl font-bold text-utama flex items-center gap-3 mb-6">
        <i class="fa-solid fa-box-archive"></i>
        Dashboard
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-utama p-6 rounded-lg shadow-md hover:scale-105 transition-transform flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-kedua">Total Peminjaman Saya</p>
                <p class="text-3xl font-bold mt-2 text-kedua">{{ $peminjamanTerakhir->count() }}</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-kedua rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-hand-holding text-utama text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-utama p-6 rounded-lg shadow-md hover:scale-105 transition-transform flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-kedua">Peminjaman Aktif</p>
                <p class="text-3xl font-bold mt-2 text-kedua">{{ $peminjamanTerakhir->where('status_peminjaman', 'aktif')->count() }}</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-kedua rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-clock text-utama text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-utama p-6 rounded-lg shadow-md hover:scale-105 transition-transform flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-kedua">Peminjaman Pending</p>
                <p class="text-3xl font-bold mt-2 text-kedua">{{ $peminjamanTerakhir->where('status_peminjaman', 'pending')->count() }}</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-kedua rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-hourglass-half text-utama text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-utama p-6 rounded-lg shadow-md hover:scale-105 transition-transform flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-kedua">Peminjaman Selesai</p>
                <p class="text-3xl font-bold mt-2 text-kedua">{{ $peminjamanTerakhir->where('status_peminjaman', 'selesai')->count() }}</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-kedua rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-utama text-3xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-utama p-6 rounded-lg shadow-md hover:scale-105 transition-transform flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-kedua">Peminjaman Ditolak</p>
                <p class="text-3xl font-bold mt-2 text-kedua">{{ $peminjamanTerakhir->where('status_peminjaman', 'ditolak')->count() }}</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-kedua rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-ban text-utama text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
            timer: 2500,
            showConfirmButton: false
        });
    @elseif(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: @json(session('error')),
            timer: 2500,
            showConfirmButton: false
        });
    @endif
</script>
@endpush

@endsection