@extends('layouts.admin')

@section('title', 'Transaksi Peminjaman')

@section('content-admin')
<div class="p-6 font-utama">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-utama flex items-center gap-3">
            <i class="fa-solid fa-handshake"></i>
            Transaksi Peminjaman
        </h1>
        <div class="flex flex-wrap gap-3 w-full sm:w-auto">
            <button onclick="openTambah()" class="bg-ketiga text-utama px-4 py-2 rounded-lg flex items-center gap-2 hover:scale-105 transition">
                <i class="fa-solid fa-plus-circle"></i>
                Tambah Peminjaman
            </button>
        </div>
    </div>

    <form method="GET" class="mb-6 bg-utama p-4 rounded-lg border border-kedua shadow-md flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fa-solid fa-search absolute left-3 top-3 text-kedua"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari peminjam / ID..." class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
        </div>
        <div class="w-full sm:w-48 relative">
            <i class="fa-solid fa-info-circle absolute left-3 top-3 text-kedua"></i>
            <select name="status" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-kedua text-utama rounded-lg hover:scale-105 transition flex items-center gap-2">
            <i class="fa-solid fa-filter"></i>
            Filter
        </button>
        <a href="{{ route('management.peminjaman.index') }}" class="px-4 py-2 bg-gray-400 text-kedua rounded-lg hover:scale-105 transition flex items-center gap-2">
            <i class="fa-solid fa-rotate-right"></i>
            Reset
        </a>
    </form>

    <div class="overflow-x-auto border border-kedua rounded-lg shadow-md bg-utama">
        <table class="w-full text-sm text-kedua">
            <thead class="bg-ketiga border-b border-kedua">
                <tr>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-hashtag mr-2"></i> ID</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-user mr-2"></i> Peminjam</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-door-open mr-2"></i> Ruangan</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-calendar-day mr-2"></i> Tanggal Pinjam</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-calendar-check mr-2"></i> Tanggal Kembali</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell">
                    <i class="fa-solid fa-info-circle mr-2"></i> Status</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-center text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-cog mr-1"></i> Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $p)
                <tr class="border-b border-kedua hover:bg-kedua transition">
                    <td class="px-2 sm:px-4 py-3 text-sm whitespace-nowrap"><i class="fa-solid fa-hashtag mr-2 text-kedua"></i>{{ $p->id_peminjaman }}</td>
                    <td class="px-2 sm:px-4 py-3 text-sm"><div class="max-w-[100px] sm:max-w-none truncate flex items-center gap-2"><i class="fa-solid fa-user mr-2 text-kedua"></i>{{ $p->pengguna->nama_pengguna ?? '-' }}</div></td>
                    <td class="px-2 sm:px-4 py-3 text-sm hidden md:table-cell"><div class="max-w-[120px] sm:max-w-none truncate flex items-center gap-2"><i class="fa-solid fa-door-open mr-2 text-kedua"></i>{{ $p->ruangan->nama_ruangan ?? '-' }}</div></td>
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-calendar-day mr-2 text-kedua"></i>{{ $p->tanggal_pinjam_formatted }}</td>
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-calendar-check mr-2 text-kedua"></i>{{ $p->tanggal_kembali_formatted }}</td>
                    <td class="px-2 sm:px-4 py-3 hidden md:table-cell">{!! $p->status_badge !!}</td>
                    <td class="px-2 sm:px-4 py-3 text-center whitespace-nowrap relative">
    <div class="flex gap-1">

        {{-- DETAIL --}}
        <button
            onclick="openDetail({{ $p->id_peminjaman }})"
            class="bg-blue-600 text-white p-1.5 rounded text-xs">
            <i class="fa-solid fa-eye"></i>
        </button>

        {{-- STATUS: pending --}}
        @if($p->status_peminjaman === 'pending')

            {{-- SETUJUI (pakai aktivasi-form) --}}
            <form action="{{ route('management.peminjaman.aktifkan', $p->id_peminjaman) }}"
                  method="POST"
                  class="aktivasi-form">
                @csrf
                <button type="submit"
                        class="bg-green-600 text-white p-1.5 rounded text-xs">
                    <i class="fa-solid fa-check"></i>
                </button>
            </form>

            {{-- TOLAK --}}
            <button
                onclick="openTolakModal({{ $p->id_peminjaman }})"
                class="bg-red-600 text-white p-1.5 rounded text-xs">
                <i class="fa-solid fa-xmark"></i>
            </button>

        @endif

        {{-- STATUS: aktif --}}
        @if($p->status_peminjaman === 'aktif')

            {{-- KEMBALIKAN --}}
            <form action="{{ route('management.peminjaman.kembali', $p->id_peminjaman) }}"
                  method="POST"
                  class="pengembalian-form">
                @csrf
                <button type="submit"
                        class="bg-yellow-500 text-white p-1.5 rounded text-xs">
                    <i class="fa-solid fa-undo"></i>
                </button>
            </form>

        @endif

        {{-- STATUS: ditolak / selesai --}}
        @if(in_array($p->status_peminjaman, ['ditolak', 'selesai']))

            {{-- HAPUS --}}
            <form action="{{ route('management.peminjaman.destroy', $p->id_peminjaman) }}"
                  method="POST">

                @csrf
                @method('DELETE')

                <button type="button"
                        onclick="confirmDelete(this)"
                        class="bg-red-700 text-white p-1.5 rounded text-xs">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>

        @endif

    </div>
</td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-kedua text-xs sm:text-sm">
                        <i class="fa-solid fa-inbox text-4xl mb-3 opacity-50"></i>
                        <p>Belum ada data peminjaman</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $peminjaman->appends(request()->query())->links() }}</div>

    <div id="overlay" class="hidden fixed inset-0 z-40 backdrop-blur-md" onclick="closeModal()"></div>

    @foreach($peminjaman as $p)
    <div id="modalDetail{{ $p->id_peminjaman }}" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-4xl overflow-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold"><i class="fa-solid fa-list mr-2"></i>Detail Peminjaman</h3>
            </div>
            <div class="mb-4 p-4 bg-kedua rounded-lg border border-kedua">
                <p class="text-sm mb-2"><i class="fa-solid fa-hashtag mr-2"></i><strong>ID:</strong> #{{ $p->id_peminjaman }}</p>
                <p class="text-sm mb-2"><i class="fa-solid fa-user mr-2"></i><strong>Peminjam:</strong> {{ $p->pengguna->nama_pengguna ?? '-' }} ({{ $p->pengguna->role_pengguna ?? '-' }})</p>
                <p class="text-sm mb-2"><i class="fa-solid fa-door-open mr-2"></i><strong>Ruangan:</strong> {{ $p->ruangan->nama_ruangan ?? '-' }}</p>
                <p class="text-sm mb-2"><i class="fa-solid fa-calendar mr-2"></i><strong>Tanggal Pinjam:</strong> {{ $p->tanggal_pinjam_formatted }}</p>
                <p class="text-sm mb-2"><i class="fa-solid fa-calendar-check mr-2"></i><strong>Tanggal Kembali:</strong> {{ $p->tanggal_kembali_formatted }}</p>
                <p class="text-sm mb-2"><i class="fa-solid fa-circle-info mr-2"></i><strong>Status:</strong> {!! $p->status_badge !!}</p>
                <p class="text-sm"><i class="fa-solid fa-comment mr-2"></i><strong>Keterangan:</strong> {{ $p->keterangan ?? '-' }}</p>
            </div>
            <ul class="space-y-3">
                @forelse($p->detailPeminjaman as $detail)
                    <li class="border border-kedua rounded-lg p-4 bg-kedua">
                        <p class="font-bold text-lg mb-3"><i class="fa-solid fa-box mr-2"></i>{{ $detail->barang->nama_barang ?? '-' }}</p>
                        <p class="text-sm mb-2"><i class="fa-solid fa-cubes mr-2"></i><strong>Jumlah:</strong> {{ $detail->jumlah_barang }} unit</p>
                    </li>
                @empty
                    <li class="text-center py-8 text-kedua"><i class="fa-solid fa-inbox text-4xl mb-3 block"></i><p>Tidak ada barang</p></li>
                @endforelse
            </ul>
            <div class="mt-4 text-right">
                <button onclick="closeAllModals()" class="px-4 py-2 bg-gray-500 text-kedua rounded-lg"><i class="fa-solid fa-xmark mr-2"></i>Tutup</button>
            </div>
        </div>
    </div>
    @endforeach

    <div id="modalTambah" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg border border-kedua w-full max-w-4xl overflow-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-4 border-b border-kedua pb-3">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <i class="fa-solid fa-plus-square text-kedua"></i>
                    Ajukan Peminjaman
                </h3>
            </div>
            <form id="formTambah" action="{{ route('management.peminjaman.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3 top-3 text-kedua"></i>
                        <select name="id_pengguna" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                            <option value="">Pilih Peminjam</option>
                            @foreach($pengguna as $pg)
                                <option value="{{ $pg->id_pengguna }}">{{ $pg->nama_pengguna }} ({{ $pg->role_pengguna }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-door-open absolute left-3 top-3 text-kedua"></i>
                        <select name="id_ruangan" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                            <option value="">Pilih Ruangan</option>
                            @foreach($ruangan as $r)
                                <option value="{{ $r->id_ruangan }}">{{ $r->nama_ruangan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-calendar absolute left-3 top-3 text-kedua"></i>
                        <input name="tanggal_pinjam" type="date" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama" value="{{ today()->format('Y-m-d') }}">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-calendar-check absolute left-3 top-3 text-kedua"></i>
                        <input name="tanggal_kembali" type="date" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                    </div>
                </div>

                <div class="relative mt-4">
                    <i class="fa-solid fa-comment absolute left-3 top-3 text-kedua"></i>
                    <textarea name="keterangan" placeholder="Keterangan (opsional)" rows="3" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua"></textarea>
                </div>

                <hr class="my-5 border-kedua">

                <div>
                    <h3 class="text-lg font-semibold mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-box-open text-kedua"></i>
                        Barang yang Diajukan
                    </h3>
                    <div class="overflow-x-auto border border-kedua rounded-lg">
                        <table class="w-full text-sm text-kedua" id="item-table">
                            <thead class="bg-kedua border-b border-kedua">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs">
                                        <i class="fa-solid fa-cube mr-1"></i> Barang
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs">
                                        <i class="fa-solid fa-sort-numeric-up mr-1"></i> Jumlah
                                    </th>
                                    <th class="px-4 py-2 text-center text-xs">
                                        <i class="fa-solid fa-cogs mr-1"></i> Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-kedua">
                                    <td class="px-4 py-2">
                                        <select name="items[0][id_barang]" class="barang-select w-full px-3 py-2 border border-kedua rounded-lg bg-utama text-sm" required>
                                            <option value="">Pilih Barang</option>
                                            @foreach($barang as $b)
                                                <option value="{{ $b->id_barang }}" data-stok="{{ $b->stok_barang }}">
                                                    {{ $b->nama_barang }} (Stok: {{ $b->stok_barang }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" name="items[0][jumlah]" class="jumlah w-full px-3 py-2 border border-kedua rounded-lg bg-utama text-sm" min="1" required>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button type="button" class="remove-row text-red-500 hover:scale-110 flex items-center gap-1 mx-auto">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" id="add-row" class="mt-3 px-3 py-2 bg-kedua text-utama rounded-lg flex items-center gap-2 hover:scale-105 transition text-sm">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Barang
                    </button>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <div class="flex gap-2 sm:ml-auto">
                        <button type="button" onclick="closeAllModals()" class="flex-1 sm:flex-none px-4 py-2 bg-gray-500 text-kedua rounded-lg flex items-center gap-2 justify-center">
                            <i class="fa-solid fa-times"></i> Batal
                        </button>
                        <button type="submit" class="flex-1 sm:flex-none px-4 py-2 bg-green-500 text-kedua rounded-lg flex items-center gap-2 justify-center">
                            <i class="fa-solid fa-paper-plane"></i> Ajukan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="modalTolak" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama border border-kedua rounded-xl shadow-2xl w-full max-w-lg p-6">
            <div class="flex justify-between items-center mb-5 border-b border-kedua pb-3">
                <h3 class="text-xl font-bold text-red-600 flex items-center gap-2">
                    <i class="fa-solid fa-ban"></i>
                    Tolak Peminjaman
                </h3>
                <button type="button" onclick="closeAllModals()" class="text-kedua hover:text-red-600">
                    <i class="fa-solid fa-times-circle text-xl"></i>
                </button>
            </div>

            <form id="formTolak" action="" method="POST">
                @csrf
                <input type="hidden" name="keterangan_tolak_required" value="1">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-kedua mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-comment-dots"></i>
                        Alasan Penolakan <span class="text-kedua">*</span>
                    </label>
                    <textarea name="keterangan_tolak" class="w-full px-4 py-3 bg-utama text-kedua border border-white rounded-lg focus:ring-2 focus:ring-red-500" rows="5" placeholder="Tuliskan alasan penolakan..." required></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeAllModals()" class="px-5 py-2 bg-gray-500 text-kedua rounded-lg hover:bg-gray-600 flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-red-500 text-kedua rounded-lg hover:bg-red-700 flex items-center gap-2">
                        <i class="fa-solid fa-times-circle"></i> Tolak Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SweetAlert2 CDN + Script LENGKAP --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Auto hide flash message
    setTimeout(() => document.getElementById('flash')?.remove(), 5000);

    let index = 1;

    function openTambah() {
        closeAllModals();
        document.getElementById('overlay').classList.remove('hidden');
        document.getElementById('modalTambah').classList.remove('hidden');
    }

    function openDetail(id) {
        closeAllModals();
        document.getElementById('overlay').classList.remove('hidden');
        document.getElementById('modalDetail' + id).classList.remove('hidden');
    }

    function closeAllModals() {
        document.getElementById('overlay').classList.add('hidden');
        document.getElementById('modalTambah').classList.add('hidden');
        document.getElementById('modalTolak').classList.add('hidden');
        document.querySelectorAll('[id^="modalDetail"]').forEach(m => m.classList.add('hidden'));
    }

    function openTolakModal(id) {
        closeAllModals();
        document.getElementById('overlay').classList.remove('hidden');
        document.getElementById('modalTolak').classList.remove('hidden');
        document.getElementById('formTolak').action = `{{ url('management/peminjaman') }}/${id}/tolak`;
    }

    // Tambah baris barang
    document.getElementById('add-row').onclick = () => {
        const tbody = document.querySelector('#item-table tbody');
        const row = document.createElement('tr');
        row.className = 'border-b border-kedua';
        row.innerHTML = `
            <td class="px-4 py-2">
                <select name="items[${index}][id_barang]" class="barang-select w-full px-3 py-2 border border-kedua rounded-lg bg-utama text-sm" required>
                    <option value="">Pilih Barang</option>
                    @foreach($barang as $b)
                        <option value="{{ $b->id_barang }}" data-stok="{{ $b->stok_barang }}">
                            {{ $b->nama_barang }} (Stok: {{ $b->stok_barang }})
                        </option>
                    @endforeach
                </select>
            </td>
            <td class="px-4 py-2">
                <input type="number" name="items[${index}][jumlah]" class="jumlah w-full px-3 py-2 border border-kedua rounded-lg bg-utama text-sm" min="1" required>
            </td>
            <td class="px-4 py-2 text-center">
                <button type="button" class="remove-row text-pendukung-1 hover:scale-110 flex items-center gap-1 mx-auto">
                    <i class="fa-solid fa-trash"></i> Hapus
                </button>
            </td>
        `;
        tbody.appendChild(row);
        index++;
    };

    // Hapus baris
    document.addEventListener('click', e => {
        if (e.target.closest('.remove-row')) {
            e.target.closest('tr').remove();
        }
    });

    // Validasi stok
    document.addEventListener('change', e => {
        if (e.target.classList.contains('barang-select')) {
            const selected = e.target.selectedOptions[0];
            const stok = selected ? selected.dataset.stok : 0;
            const jumlahInput = e.target.closest('tr').querySelector('.jumlah');
            jumlahInput.max = stok;
            if (parseInt(jumlahInput.value) > stok && jumlahInput.value !== '') {
                jumlahInput.value = stok;
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Tidak Cukup!',
                    text: `Stok barang ini hanya ${stok} unit.`,
                    timer: 2500,
                    showConfirmButton: false
                });
            }
        }
    });

    // SweetAlert Aktivasi
    document.querySelectorAll('.aktivasi-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Aktifkan Peminjaman?',
                text: "Stok barang akan otomatis dikurangi setelah diaktifkan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Aktifkan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // SweetAlert Pengembalian
    document.querySelectorAll('.pengembalian-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Kembalikan Barang?',
                text: "Status akan berubah menjadi Selesai dan stok akan ditambah kembali.",
                icon: 'info',
                showCancelButton: true,
                timer: 2500,
                confirmButtonText: 'Ya, Kembalikan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
 
    // SweetAlert Tolak
    document.getElementById('formTolak')?.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Tolak Peminjaman Ini?',
            text: "Peminjam akan menerima notifikasi bahwa pengajuannya ditolak.",
            icon: 'warning',
            showCancelButton: true,
            timer:2500,
            confirmButtonColor: '#ef4444',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Tolak',
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    function confirmDelete(button) {
    Swal.fire({
        title: 'Hapus Peminjaman?',
        text: "Data peminjaman akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya, Hapus',
        background: '#1E3A8A',
        color: '#FFFFFF'
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}


    // Escape key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeAllModals();
    });
</script>
@endsection
