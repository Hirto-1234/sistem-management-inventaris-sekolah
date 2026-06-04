@extends('layouts.admin')

@section('title', 'Riwayat Barang Masuk')

@section('content-admin')
<div class="p-6 font-utama">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-utama"><i class="fa-solid fa-box-archive mr-3"></i>Riwayat Barang Masuk</h1>
        <div class="flex flex-wrap gap-3 w-full sm:w-auto">
            <button onclick="openModalTambah()" class="bg-ketiga text-utama px-4 py-2 rounded-lg flex items-center hover:scale-105 transition">
                <i class="fa-solid fa-plus mr-2"></i>Tambah Stok
            </button>
        </div>
    </div>

    <form method="GET" class="mb-6 bg-utama p-4 rounded-lg border border-kedua shadow-md flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fa-solid fa-search absolute left-3 top-3 text-kedua"></i>
            <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari nama barang..." class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
        </div>

        <div class="flex-1 relative">
            <i class="fa-solid fa-calendar absolute left-3 top-3 text-kedua"></i>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
        </div>

        <button type="submit" class="px-4 py-2 bg-kedua text-utama rounded-lg hover:scale-105 transition"><i class="fa-solid fa-filter mr-2"></i>Filter</button>
        <a href="{{ route('management.barang-masuk.index') }}" class="px-4 py-2 bg-gray-400 text-kedua rounded-lg hover:scale-105 transition"><i class="fa-solid fa-rotate-right mr-2"></i>Reset</a>
    </form>

    {{-- FLASH MESSAGE DENGAN SWEETALERT --}}
    @if(session('success'))
        <div class="hidden" id="swal-success">{{ session('success') }}</div>
    @endif
    @if(session('error_store'))
        <div class="hidden" id="swal-error">{{ session('error_store') }}</div>
    @endif
    @if($errors->any())
        <div class="hidden" id="swal-validation">
            Terjadi kesalahan:<br>
            <ul class="text-left mt-2">
                @foreach($errors->all() as $err)
                    <li>• {{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-x-auto border border-kedua rounded-lg shadow-md bg-utama">
        <table class="w-full text-sm text-kedua">
            <thead class="bg-ketiga border-b border-kedua">
                <tr>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-hashtag mr-2"></i>ID</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-box mr-2"></i>Nama Barang</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-user mr-2"></i>Diinput Oleh</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-calendar mr-2"></i>Tanggal Masuk</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-cubes mr-2"></i>Jumlah Masuk</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-handshake mr-2"></i>Sumber</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-center text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-cog mr-2"></i>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $barangMasuk)
                <tr class="border-b border-kedua hover:bg-kedua transition">
                    <td class="px-2 sm:px-4 py-3 text-sm whitespace-nowrap"><i class="fa-solid fa-hashtag mr-2"></i>{{ $barangMasuk->id_barang_masuk }}</td>
                    <td class="px-2 sm:px-4 py-3 text-sm">
                        <div class="max-w-[150px] md:max-w-none truncate"><i class="fa-solid fa-box mr-2"></i>{{ ucfirst($barangMasuk->barang->nama_barang) }}</div>
                    </td>
                    <td class="px-2 sm:px-4 py-3 text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-user mr-2"></i>{{ $barangMasuk->pengguna->nama_pengguna }}</td>
                    <td class="px-2 sm:px-4 py-3 text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-calendar mr-2"></i>{{ $barangMasuk->tanggal_masuk->toDateString() }}</td>
                    <td class="px-2 sm:px-4 py-3 text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-cubes mr-2"></i>{{ $barangMasuk->jumlah_masuk }}</td>
                    <td class="px-2 sm:px-4 py-3 text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-handshake mr-2"></i>{{ $barangMasuk->sumber }}</td>
                    <td class="px-2 sm:px-4 py-3 text-center whitespace-nowrap">
                        <button onclick="bukaModal('modalDetail{{ $barangMasuk->id_barang_masuk }}')" 
                            class="bg-blue-600 p-1.5 rounded hover:scale-110" title="Detail">
                            <i class="fa-solid fa-eye text-xs sm:text-sm"></i>
                        </button>
                        <form action="{{ route('management.barang-masuk.delete', $barangMasuk->id_barang_masuk) }}" method="POST" class="inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="hover:scale-110 ml-2 bg-red-500 p-1.5 rounded" title="Hapus">
                                <i class="fa-solid fa-trash text-xs sm:text-sm"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-4 text-center text-kedua text-xs sm:text-sm"><i class="fa-solid fa-inbox mr-2"></i>Belum ada data barang</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $barangs->appends(request()->query())->links() }}</div>

    <div id="overlay" class="hidden fixed inset-0 z-40 backdrop-blur-md" onclick="closeModal()"></div>

    <div id="modalTambah" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-4xl overflow-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold"><i class="fa-solid fa-plus mr-2"></i>Tambah Stok Barang</h3>
            </div>
            <form id="formTambah" action="{{ route('management.barang-masuk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative">
                        <i class="fa-solid fa-layer-group absolute left-3 top-3 text-kedua"></i>
                        <select name="id_barang" id="pilihBarangMasuk" onchange="tampilkanStokBarang()" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                            <option value="">Pilih Barang</option>
                        </select>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-cubes absolute left-3 top-3 text-kedua"></i>
                        <input name="jumlah_barang" type="number" min="1" placeholder="Jumlah" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua" id="jumlah_tambah">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-wallet absolute left-3 top-3 text-kedua"></i>
                        <input name="asal_dana" placeholder="Asal Dana" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <div class="flex gap-2 sm:ml-auto">
                        <button type="button" onclick="closeModal()" class="flex-1 sm:flex-none px-4 py-2 bg-gray-500 text-kedua rounded-lg"><i class="fa-solid fa-xmark mr-2"></i>Batal</button>
                        <button type="submit" class="flex-1 sm:flex-none px-4 py-2 bg-green-500 text-kedua rounded-lg"><i class="fa-solid fa-save mr-2"></i>Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @foreach($barangs as $barangMasuk)
        <div id="modalDetail{{ $barangMasuk->id_barang_masuk }}" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
            <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-2xl overflow-auto max-h-[90vh]">
                <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-list mr-2"></i>Detail Barang Masuk</h3>
                
                <div class="p-4 bg-kedua rounded-lg border border-kedua mb-4">
                    <p class="text-sm mb-2">
                        <i class="fa-solid fa-hashtag mr-2"></i>
                        <strong>ID:</strong> {{ $barangMasuk->id_barang_masuk }}
                    </p>
                    <p class="text-sm mb-2">
                        <i class="fa-solid fa-user-circle mr-2"></i>
                        <strong>Diinput Oleh:</strong> {{ $barangMasuk->pengguna->nama_pengguna ?? '-' }}
                    </p>
                    <p class="text-sm mb-2">
                        <i class="fa-solid fa-box mr-2"></i>
                        <strong>Nama Barang:</strong> {{ ucfirst($barangMasuk->barang->nama_barang ?? '-') }}
                    </p>
                    <p class="text-sm mb-2">
                        <i class="fa-solid fa-calendar mr-2"></i>
                        <strong>Tanggal Masuk:</strong> {{ \Carbon\Carbon::parse($barangMasuk->tanggal_masuk)->format('d-m-Y') }}
                    </p>
                    <p class="text-sm mb-2">
                        <i class="fa-solid fa-cubes mr-2"></i>
                        <strong>Jumlah Masuk:</strong> {{ $barangMasuk->jumlah_masuk }}
                    </p>
                    <p class="text-sm mb-2">
                        <i class="fa-solid fa-handshake mr-2"></i>
                        <strong>Sumber:</strong> {{ $barangMasuk->sumber ?? '-' }}
                    </p>
                    <p class="text-sm">
                        <i class="fa-solid fa-wallet mr-2"></i>
                        <strong>Asal Dana:</strong> {{ $barangMasuk->asal_dana ?? '-' }}
                    </p>
                </div>

                <div class="flex justify-end gap-2">
                    <button onclick="tutupSemua()" class="px-4 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition">
                        <i class="fa-solid fa-xmark mr-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- SWEETALERT2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Data barang dari server (AJAX simulation)
    const daftarBarang = {
        @foreach($dataBarang as $barang)
            {{ $barang->id_barang }}: {
                id: {{ $barang->id_barang }},
                namaBarang: "{{ $barang->nama_barang }}",
                stokTersedia: {{ $barang->stok_barang ?? 0 }}
            },
        @endforeach
    };

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
    });

    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('swal-success')) {
            Toast.fire({
                icon: 'success',
                title: document.getElementById('swal-success').innerText
            });
        }
        if (document.getElementById('swal-error')) {
            Toast.fire({
                icon: 'error',
                title: document.getElementById('swal-error').innerText
            });
        }
        if (document.getElementById('swal-validation')) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: document.getElementById('swal-validation').innerHTML
            });
        }

        // Muat daftar barang saat pertama kali
        muatDaftarBarangMasuk();
    });

    function muatDaftarBarangMasuk() {
        const selectBarang = document.getElementById('pilihBarangMasuk');
        
        if (!selectBarang) return;
        
        const opsiBarang = Object.values(daftarBarang).map(function(barang) {
            return `<option value="${barang.id}">${barang.namaBarang} (Stok: ${barang.stokTersedia})</option>`;
        }).join('');
        
        selectBarang.innerHTML = '<option value="">Pilih Barang</option>' + opsiBarang;
    }

    function tampilkanStokBarang() {
        const idBarang = document.getElementById('pilihBarangMasuk').value;
        
        if (!idBarang || !daftarBarang[idBarang]) {
            return;
        }
        
        const barang = daftarBarang[idBarang];
    }

    function openModalTambah() {
        document.getElementById('modalTambah').classList.remove('hidden');
        document.getElementById('overlay').classList.remove('hidden');
        muatDaftarBarangMasuk(); // Reload data barang saat modal dibuka
    }

    function tutupSemua() {
        document.querySelectorAll('[id^="modal"]').forEach(function(modal) {
            modal.classList.add('hidden');
        });
        document.getElementById('overlay').classList.add('hidden');
    }

    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Record data ini akan hilang permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
        
        



                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    function closeModal() {
        document.getElementById('overlay').classList.add('hidden');
        ['modalTambah', 'modalDetail'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
        });
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    function bukaModal(idModal) {
        tutupSemua();
        document.getElementById('overlay').classList.remove('hidden');
        document.getElementById(idModal).classList.remove('hidden');
    }
</script>
@endsection