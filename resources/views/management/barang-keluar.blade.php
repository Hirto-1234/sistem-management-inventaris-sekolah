@extends('layouts.admin')

@section('title', 'Riwayat Barang Keluar')

@section('content-admin')
<div class="p-6 font-utama">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-utama">
            <i class="fa-solid fa-box-open mr-3"></i>Barang Keluar
        </h1>

       <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <button onclick="bukaModal('modalTambah')" class="bg-ketiga text-utama px-4 py-2 rounded-lg flex items-center justify-center hover:scale-105 transition">
                <i class="fa-solid fa-plus mr-2"></i>Tambah Barang Keluar
            </button>
        </div>
    </div>

    <form method="GET" class="mb-6 bg-utama p-4 rounded-lg border border-kedua shadow-md flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fa-solid fa-search absolute left-3 top-3 text-kedua"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang..."
                class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
        </div>

        <div class="w-full sm:w-48 relative">
            <i class="fa-solid fa-tags absolute left-3 top-3 text-kedua"></i>
            <select name="kategori_keluar" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
                <option disabled selected ="">Semua Kategori</option>
                <option value="habis_terpakai" {{ request('kategori_keluar') == 'habis_terpakai' ? 'selected' : '' }}>Habis Terpakai</option>
                <option value="rusak_total" {{ request('kategori_keluar') == 'rusak_total' ? 'selected' : '' }}>Rusak Total</option>
                <option value="hilang" {{ request('kategori_keluar') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                <option value="kadaluarsa" {{ request('kategori_keluar') == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                <option value="aus_tidak_layak" {{ request('kategori_keluar') == 'aus_tidak_layak' ? 'selected' : '' }}>Aus/Tidak Layak</option>
                <option value="penghapusan_aset" {{ request('kategori_keluar') == 'penghapusan_aset' ? 'selected' : '' }}>Penghapusan Aset</option>
            </select>
        </div>

        <button type="submit" class="px-4 py-2 bg-kedua text-utama rounded-lg hover:scale-105 transition">
            <i class="fa-solid fa-filter mr-2"></i>Filter
        </button>

        <a href="{{ route('management.barang-keluar.index') }}" class="px-4 py-2 bg-gray-400 text-kedua rounded-lg hover:scale-105 transition text-center">
            <i class="fa-solid fa-rotate-right mr-2"></i>Reset
        </a>
    </form>

    <div class="overflow-x-auto border border-kedua rounded-lg shadow-md bg-utama">
        <table class="w-full text-sm text-kedua">
            <thead class="bg-ketiga border-b border-kedua">
                <tr>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-hashtag mr-2"></i>ID</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-box mr-2"></i>Barang</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-door-open mr-2"></i>Ruangan</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-cubes mr-2"></i>Jumlah</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-tags mr-2"></i>Kategori</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-center text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-cog mr-2"></i>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($daftarBarangKeluar as $barangKeluar)
                <tr class="border-b border-kedua hover:bg-kedua transition">
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap">
                        <i class="fa-solid fa-hashtag mr-2"></i>{{ $barangKeluar->id_barang_keluar }}
                    </td>
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap">
                        <i class="fa-solid fa-box mr-2"></i>{{ ucfirst($barangKeluar->inventarisRuangan->barang->nama_barang ?? '-') }}
                    </td>
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap hidden md:table-cell">
                        <i class="fa-solid fa-door-open mr-2"></i>{{ ucfirst($barangKeluar->inventarisRuangan->ruangan->nama_ruangan ?? '-') }}
                    </td>
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap hidden md:table-cell">
                        <i class="fa-solid fa-cubes mr-2"></i>{{ $barangKeluar->jumlah_keluar ?? '-' }}
                    </td>
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap hidden md:table-cell">
                        @php
                            $kategoriBadges = [
                                'habis_terpakai'   => ['class' => 'bg-blue-100 text-blue-800',   'icon' => 'fa-box-open'],
                                'rusak_total'      => ['class' => 'bg-red-100 text-red-800',     'icon' => 'fa-triangle-exclamation'],
                                'hilang'           => ['class' => 'bg-yellow-100 text-yellow-800','icon' => 'fa-magnifying-glass'],
                                'kadaluarsa'       => ['class' => 'bg-orange-100 text-orange-800','icon' => 'fa-clock-rotate-left'],
                                'aus_tidak_layak'  => ['class' => 'bg-gray-100 text-gray-800',   'icon' => 'fa-tools'],
                                'penghapusan_aset' => ['class' => 'bg-purple-100 text-purple-800','icon' => 'fa-trash']
                            ];

                            $kat = $barangKeluar->kategori_keluar;
                            $badge = $kategoriBadges[$kat] ?? ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-circle-info'];
                        @endphp

                        <span class="px-2 py-1 rounded-full text-xs inline-flex items-center gap-1 w-fit {{ $badge['class'] }}">
                            <i class="fa-solid {{ $badge['icon'] }} text-[10px]"></i>
                            {{ str_replace('_', ' ', ucwords($kat ?? '-')) }}
                        </span>
                    </td>
                    <td class="px-2 sm:px-4 py-3 text-center whitespace-nowrap">
                        <button onclick="bukaModal('modalDetail{{ $barangKeluar->id_barang_keluar }}')" 
                            class="hover:scale-110 bg-blue-600 p-1.5 rounded" title="Detail">
                            <i class="fa-solid fa-eye text-xs sm:text-sm"></i>
                        </button>
                        <button onclick="konfirmasiHapus({{ $barangKeluar->id_barang_keluar }})" 
                            class="hover:scale-110 ml-2 bg-red-500 rounded p-1.5" title="Hapus">
                            <i class="fa-solid fa-trash text-xs sm:text-sm"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-kedua text-xs sm:text-sm">
                        <i class="fa-solid fa-inbox mr-2"></i>Belum ada data barang keluar
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $daftarBarangKeluar->appends(request()->query())->links() }}</div>

    <div id="overlay" class="hidden fixed inset-0 z-40 backdrop-blur-md" onclick="tutupSemua()"></div>

    @foreach($daftarBarangKeluar as $barangKeluar)
        <div id="modalDetail{{ $barangKeluar->id_barang_keluar }}" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
            <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-2xl overflow-auto max-h-[90vh]">
                <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-list mr-2"></i>Detail Barang Keluar</h3>
                
                <div class="p-4 bg-kedua rounded-lg border border-kedua mb-4">
                    <p class="text-sm mb-2">
                        <i class="fa-solid fa-hashtag mr-2"></i>
                        <strong>ID:</strong> {{ $barangKeluar->id_barang_keluar }}
                    </p>
                    <p class="text-sm mb-2">
                        <i class="fa-solid fa-user-circle mr-2"></i>
                        <strong>Diinput Oleh:</strong> {{ $barangKeluar->pengguna->nama_pengguna ?? '-' }}
                    </p>
                    <p class="text-sm mb-2">
                        <i class="fa-solid fa-box mr-2"></i>
                        <strong>Nama Barang:</strong> {{ ucfirst($barangKeluar->inventarisRuangan->barang->nama_barang ?? '-') }}
                    </p>
                    <p class="text-sm mb-2">
                        <i class="fa-solid fa-door-open mr-2"></i>
                        <strong>Ruangan:</strong> {{ ucfirst($barangKeluar->inventarisRuangan->ruangan->nama_ruangan ?? '-') }}
                    </p>
                    <p class="text-sm mb-2">
                        <i class="fa-solid fa-calendar mr-2"></i>
                        <strong>Tanggal Keluar:</strong> {{ \Carbon\Carbon::parse($barangKeluar->tanggal_keluar)->format('d-m-Y H:i') }}
                    </p>
                    <p class="text-sm mb-2">
                        <i class="fa-solid fa-cubes mr-2"></i>
                        <strong>Jumlah Keluar:</strong> {{ $barangKeluar->jumlah_keluar }}
                    </p>
                    <p class="text-sm mb-2">
                        <i class="fa-solid fa-tags mr-2"></i>
                        <strong>Kategori:</strong>

                        @php
                            $kategoriBadges = [
                                'habis_terpakai'     => ['class' => 'bg-blue-100 text-blue-800',    'icon' => 'fa-box-open'],
                                'rusak_total'        => ['class' => 'bg-red-100 text-red-800',      'icon' => 'fa-triangle-exclamation'],
                                'hilang'             => ['class' => 'bg-yellow-100 text-yellow-800','icon' => 'fa-magnifying-glass'],
                                'kadaluarsa'         => ['class' => 'bg-orange-100 text-orange-800','icon' => 'fa-clock-rotate-left'],
                                'aus_tidak_layak'    => ['class' => 'bg-gray-100 text-gray-800',    'icon' => 'fa-tools'],
                                'penghapusan_aset'   => ['class' => 'bg-purple-100 text-purple-800','icon' => 'fa-trash'],
                            ];

                            $kat = $barangKeluar->kategori_keluar;
                            $badge = $kategoriBadges[$kat] ?? ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-circle-info'];
                        @endphp

                        <span class="px-2 py-1 rounded-full text- inline-flex items-center gap-1 {{ $badge['class'] }}">
                            <i class="fa-solid {{ $badge['icon'] }} text-xs"></i>
                            {{ str_replace('_', ' ', ucwords($kat ?? '-')) }}
                        </span>
                    </p>

                    <p class="text-sm">
                        <i class="fa-solid fa-comment mr-2"></i>
                        <strong>Keterangan:</strong> {{ $barangKeluar->keterangan ?? '-' }}
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

    <div id="modalTambah" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-4xl overflow-auto max-h-[90vh]">
            <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-plus mr-2"></i>Tambah Barang Keluar</h3>
            <form action="{{ route('management.barang-keluar.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="relative">
                        <i class="fa-solid fa-door-open absolute left-3 top-3 text-kedua"></i>
                        <select id="pilihRuangan" onchange="muatDaftarBarang()" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama" required>
                            <option disabled selected value="">Pilih Ruangan</option>
                            @foreach($daftarInventaris->groupBy('id_ruangan') as $idRuangan => $inventarisPerRuangan)
                                <option value="{{ $idRuangan }}">{{ ucfirst($inventarisPerRuangan[0]->ruangan->nama_ruangan ?? '-') }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="relative">
                        <i class="fa-solid fa-box absolute left-3 top-3 text-kedua"></i>
                        <select id="pilihBarang" name="id_inventaris_ruangan" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama" required>
                            <option disabled selected value="">Pilih Ruangan Dulu</option>
                        </select>
                    </div>
                    
                    <div class="relative">
                        <i class="fa-solid fa-cubes absolute left-3 top-3 text-kedua"></i>
                        <input type="number" min="1" name="jumlah_barang" placeholder="Jumlah Barang" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua" required>
                    </div>
                    
                    <div class="relative">
                        <i class="fa-solid fa-tags absolute left-3 top-3 text-kedua"></i>
                        <select name="kategori_keluar" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama" required>
                            <option disabled selected value="">Pilih Kategori Keluar</option>
                            <option value="habis_terpakai">Habis Terpakai</option>
                            <option value="rusak_total">Rusak Total</option>
                            <option value="hilang">Hilang</option>
                            <option value="kadaluarsa">Kadaluarsa</option>
                            <option value="aus_tidak_layak">Aus/Tidak Layak</option>
                            <option value="penghapusan_aset">Penghapusan Aset</option>
                        </select>
                    </div>
                    
                    <div class="relative md:col-span-2">
                        <i class="fa-solid fa-comment absolute left-3 top-3 text-kedua"></i>
                        <textarea name="keterangan" id="keterangan" placeholder="Keterangan (Opsional)" rows="3" maxlength="1000" class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" onclick="tutupSemua()" class="px-5 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition">
                        <i class="fa-solid fa-xmark mr-2"></i>Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-green-500 text-kedua rounded-lg hover:scale-105 transition">
                        <i class="fa-solid fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
const daftarInventaris = {
    @foreach($daftarInventaris->groupBy('id_ruangan') as $idRuangan => $inventarisPerRuangan)
        {{ $idRuangan }}: [
            @foreach($inventarisPerRuangan as $inventaris)
                {
                    id: {{ $inventaris->id_inventaris_ruangan }}, 
                    namaBarang: "{{ $inventaris->barang->nama_barang ?? '-' }}", 
                    stokTersedia: {{ $inventaris->jumlah_barang }}
                },
            @endforeach
        ],
    @endforeach
};

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        tutupSemua();
    }
});

function bukaModal(idModal) {
    tutupSemua();
    document.getElementById('overlay').classList.remove('hidden');
    document.getElementById(idModal).classList.remove('hidden');
}

function tutupSemua() {
    document.querySelectorAll('[id^="modal"]').forEach(function(modal) {
        modal.classList.add('hidden');
    });
    document.getElementById('overlay').classList.add('hidden');
}

function muatDaftarBarang() {
    const idRuangan = document.getElementById('pilihRuangan').value;
    const selectBarang = document.getElementById('pilihBarang');
    
    if (!idRuangan || !daftarInventaris[idRuangan]) {
        selectBarang.innerHTML = '<option value="">Pilih Ruangan Dulu</option>';
        return;
    }
    
    const opsiBarang = daftarInventaris[idRuangan].map(function(barang) {
        return `<option value="${barang.id}">${barang.namaBarang} (Stok: ${barang.stokTersedia})</option>`;
    }).join('');
    
    selectBarang.innerHTML = '<option value disabled selected="">Pilih Barang</option>' + opsiBarang;
}

function konfirmasiHapus(idBarangKeluar) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Data barang keluar akan dihapus permanen dan stok tidak akan dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fa-solid fa-trash mr-2"></i>Hapus',
        cancelButtonText: '<i class="fa-solid fa-xmark mr-2"></i>Batal',
        background: '#1E3A8A',
        color: '#FFFFFF'
    }).then((result) => {
        if (result.isConfirmed) {
            const formHapus = document.createElement('form');
            formHapus.method = 'POST';
            formHapus.action = "{{ url('management/barang-keluar') }}/" + idBarangKeluar;
            formHapus.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(formHapus);
            formHapus.submit();
        }
    });
}
</script>
@endsection