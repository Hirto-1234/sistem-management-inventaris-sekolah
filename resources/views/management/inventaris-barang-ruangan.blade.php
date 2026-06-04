@extends('layouts.admin')

@section('title', 'Inventaris Barang Berdasarkan Ruangan')

@section('content-admin')
<div class="p-6 font-utama">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-utama">
            <i class="fa-solid fa-door-open mr-3"></i>Inventaris Barang Berdasarkan Ruangan
        </h1>
        <button onclick="openModal('tambah')" class="w-full sm:w-auto bg-ketiga text-utama px-4 py-2 rounded-lg hover:scale-105 transition">
            <i class="fa-solid fa-plus mr-2"></i>Tambah Data
        </button>
    </div>

    <form action="{{ route('management.inventaris-ruangan.index') }}" method="GET" class="mb-6 bg-utama p-4 rounded-lg border border-kedua shadow-md flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fa-solid fa-search absolute left-3 top-3 text-kedua"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari data di ruangan..." class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
        </div>
        <button type="submit" class="px-4 py-2 bg-kedua text-utama rounded-lg hover:scale-105 transition">
            <i class="fa-solid fa-filter mr-2"></i>Filter
        </button>
        <a href="{{ route('management.inventaris-ruangan.index') }}" class="px-4 py-2 bg-gray-400 text-kedua rounded-lg hover:scale-105 transition">
            <i class="fa-solid fa-rotate-right mr-2"></i>Reset
        </a>
    </form>

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
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-door-open mr-2"></i>Nama Ruangan</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-user mr-2"></i>Diupdate Oleh</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-clock mr-2"></i>Terakhir Diupdate</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-center text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-cog mr-2"></i>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $inventaris)
                <tr class="border-b border-kedua hover:bg-kedua transition">
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap">
                        <i class="fa-solid fa-hashtag mr-2"></i>{{ $inventaris[0]->id_inventaris_ruangan }}
                    </td>
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm">
                        <div class="max-w-[150px] md:max-w-none truncate">
                            <i class="fa-solid fa-door-open mr-2"></i>{{ $inventaris[0]->ruangan->nama_ruangan }}
                        </div>
                    </td>
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm hidden md:table-cell">
                        <div class="max-w-[100px] sm:max-w-none truncate">
                            <i class="fa-solid fa-user mr-2"></i>{{ $inventaris[0]->pengguna->nama_pengguna }}
                        </div>
                    </td>
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap hidden md:table-cell">
                        <i class="fa-solid fa-clock mr-2"></i>{{ $inventaris[0]->updated_at }}
                    </td>
                    <td class="px-2 sm:px-4 py-3 text-center whitespace-nowrap">
                        <button onclick="showDetail({{ $inventaris[0]->ruangan->id_ruangan }})" class="bg-blue-600 p-1.5 rounded hover:scale-110" title="Detail">
                            <i class="fa-solid fa-eye text-xs sm:text-sm"></i>
                        </button>
                        @if($inventaris[0]->ruangan->nama_ruangan !== 'TU')
                        <button onclick="showEdit({{ $inventaris[0]->ruangan->id_ruangan }})" class="bg-yellow-500 p-1.5 rounded hover:scale-110 ml-2" title="Edit">
                            <i class="fa-solid fa-pen text-xs sm:text-sm"></i>
                        </button>
                        <button onclick="hapusRuangan('{{ $inventaris[0]->ruangan->id_ruangan }}', '{{ $inventaris[0]->ruangan->nama_ruangan }}')" class="bg-red-500 p-1.5 rounded hover:scale-110 ml-2" title="Hapus">
                            <i class="fa-solid fa-trash text-xs sm:text-sm"></i>
                        </button>
                        @endif

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-kedua text-xs sm:text-sm">
                        <i class="fa-solid fa-inbox mr-2"></i>Belum ada data ruangan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="overlay" class="hidden fixed inset-0 z-40 backdrop-blur-md" onclick="closeModal()"></div>

    <div id="modalTambah" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-4xl overflow-auto max-h-[90vh]">
            <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-plus mr-2"></i>Tambah Data Ruangan</h3>
            <form action="{{ route('management.inventaris-barang-ruangan.store') }}" method="POST">
                @csrf
                <div class="relative mb-4">
                    <i class="fa-solid fa-door-open absolute left-3 top-3 text-kedua"></i>
                    <select name="id_ruangan" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                        <option value="" selected disabled>Pilih Ruangan</option>
                        @foreach($ruangan as $r)
                            <option value="{{ $r->id_ruangan }}">{{ $r->nama_ruangan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="flex-1 relative">
                        <i class="fa-solid fa-boxes-stacked absolute left-3 top-3 text-kedua"></i>
                        <select name="barang[]" required class="select-barang w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                            <option value="" selected disabled>Pilih Barang</option>
                            @foreach($barang as $b)
                                <option value="{{ $b->id_barang }}" data-stok="{{ $b->stok_barang }}">{{ucfirst($b->nama_barang) }}</option>
                            @endforeach
                        </select>
                        <p class="text-sm text-kedua absolute bottom-3 right-6 stok-teks"></p>
                    </div>
                    <div class="w-full lg:w-32 relative">
                        <i class="fa-solid fa-cubes absolute left-3 top-3 text-kedua"></i>
                        <input name="jumlah[]" type="number" min="1" placeholder="Jumlah" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
                    </div>
                </div>

                <div id="containerTambahanBarang" class="space-y-4 mb-4"></div>

                <div class="relative mb-4">
                    <i class="fa-solid fa-circle-info absolute left-3 top-3 text-kedua"></i>
                    <textarea name="deskripsi_ruangan" placeholder="Deskripsi / Keterangan" rows="3" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua"></textarea>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" id="btnTambah" class="px-4 py-2 bg-kedua text-utama rounded-lg hover:scale-105 transition">
                        <i class="fa-solid fa-plus mr-2"></i>Tambah Barang
                    </button>
                    <div class="flex gap-2 sm:ml-auto">
                        <button type="button" onclick="closeModal()" class="px-5 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition">
                            <i class="fa-solid fa-xmark mr-2"></i>Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-green-500 text-kedua rounded-lg hover:scale-105 transition">
                            <i class="fa-solid fa-save mr-2"></i>Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEdit" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-4xl overflow-auto max-h-[90vh]">
            <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-pen mr-2"></i>Update Data Ruangan</h3>

            <form id="editForm" method="POST">
                @csrf @method('PATCH')
                <div class="grid grid-cols-1 gap-4 mb-4">
                    <div class="relative">
                        <i class="fa-solid fa-door-open absolute left-3 top-3 text-kedua"></i>
                        <input type="hidden" id="idRuanganEdit">
                        <input id="namaRuanganEdit" disabled class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                    </div>
                    <div id="containerBarangSudahAda" class="flex flex-col gap-4 md:flex-col"></div>
                    <div id="containerTambahanBarangEdit" class="flex flex-col gap-4"></div>
                    <div class="relative">
                        <i class="fa-solid fa-circle-info absolute left-3 top-3 text-kedua"></i>
                        <textarea name="deskripsi_ruangan" placeholder="Deskripsi / Keterangan" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua" rows="3"></textarea>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-between gap-3">
                    <button type="button" id="btnTambahEdit" class="px-4 py-2 bg-kedua text-utama rounded-lg hover:scale-105 transition">
                        <i class="fa-solid fa-plus mr-2"></i>Tambah Barang
                    </button>
                    <div class="flex gap-2">
                        <button type="button" onclick="closeModal()" class="px-5 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition">
                            <i class="fa-solid fa-xmark mr-2"></i>Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-yellow-500 text-kedua rounded-lg hover:scale-105 transition">
                            <i class="fa-solid fa-save mr-2"></i>Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="modalDetail" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-4xl overflow-auto max-h-[90vh]">
            <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-list mr-2"></i>Detail Barang per Ruangan</h3>

            <div class="p-4 bg-kedua rounded-lg border border-kedua mb-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-x-2">
                        <i class="fa-solid fa-hashtag fa-2xl text-gray-500"></i>
                        <div>
                            <p class="text-sm">ID Ruangan :</p>
                            <p class="text-sm font-bold" id="detailId"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-x-2">
                        <i class="fa-solid fa-door-open fa-2xl text-blue-500"></i>
                        <div>
                            <p class="text-sm">Nama Ruangan :</p>
                            <p class="text-sm font-bold" id="detailNama"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-x-2">
                        <i class="fa-solid fa-user-check fa-2xl text-green-700"></i>
                        <div>
                            <p class="text-sm">Diupdate Oleh :</p>
                            <p class="text-sm font-bold" id="detailPengguna"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-x-2">
                        <i class="fa-solid fa-clock-rotate-left fa-2xl text-gray-500"></i>
                        <div>
                            <p class="text-sm">Terakhir Diupdate Pada :</p>
                            <p class="text-sm font-bold" id="detailUpdate"></p>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="font-bold mb-3 text-lg"><i class="fa-solid fa-boxes-stacked mr-2"></i>Barang yang Tersedia</h4>
            <div id="containerBarang" class=" mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">

            </div>

            <div class="flex justify-end gap-2">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition">
                    <i class="fa-solid fa-xmark mr-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
    });

    document.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('swal-success')) {
            Toast.fire({ icon: 'success', title: document.getElementById('swal-success').innerText });
        }
        if (document.getElementById('swal-error')) {
            Toast.fire({ icon: 'error', title: document.getElementById('swal-error').innerText });
        }
        if (document.getElementById('swal-validation')) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: document.getElementById('swal-validation').innerHTML
            });
        }
    });

    setTimeout(() => document.getElementById('flash')?.remove(), 2500);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    document.querySelectorAll('.select-barang').forEach(select => {
        const stokText = select.parentElement.querySelector('.stok-teks');

        select.addEventListener('change', function() {
            const stok = this.options[this.selectedIndex].dataset.stok;
            stokText.innerText = `Stok: ${stok}`;
        });
    });

    function openModal(type) {
        closeModal();
        document.getElementById('overlay').classList.remove('hidden');
        document.getElementById('modal' + capitalize(type)).classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('overlay').classList.add('hidden');
        document.querySelectorAll('[id^="modal"]').forEach(el => el.classList.add('hidden'));
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function showDetail(id) {
        fetch(`/management/inventaris-barang-ruangan/${id}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('detailId').innerText = data.id_ruangan;
                document.getElementById('detailNama').innerText = data.nama_ruangan;
                document.getElementById('detailPengguna').innerText = data.updated_by;
                document.getElementById('detailUpdate').innerText = formatTanggal(data.updated_at);

                const container = document.getElementById('containerBarang');
                container.innerHTML = '';

                data.barangs.forEach(barang => {
                    const div = document.createElement('div');
                    div.className = 'p-4 bg-kedua rounded-lg border border-kedua';
                    div.innerHTML = `
                        <div class="flex items-center">
                            <div class="flex items-center gap-x-2">
                                <i class="fa-solid fa-box fa-xl text-teal-500"></i>
                                <div>
                                    <p class="text-sm">Nama Barang :</p>
                                    <p class="text-sm font-bold">${ucWords(barang.nama_barang)}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-x-2">
                                <i class="fa-solid fa-cubes fa-xl text-teal-500"></i>
                                <div>
                                    <p class="text-sm">Jumlah Barang :</p>
                                    <p class="text-sm font-bold">${barang.jumlah_barang}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    container.appendChild(div);
                });
            });
        openModal('detail');
    }

    function showEdit(id) {
        const form = document.getElementById('editForm');
        form.action = `/management/inventaris-barang-ruangan/${id}`;
        
        fetch(`/management/inventaris-barang-ruangan/${id}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('idRuanganEdit').value = data.id_ruangan;
                document.getElementById('namaRuanganEdit').value = data.nama_ruangan;
                
                const container = document.getElementById('containerBarangSudahAda');
                container.innerHTML = '';
                
                data.barangs.forEach(barang => {
                    const div = document.createElement('div');
                    div.className = 'flex flex-col lg:flex-row gap-4';
                    div.innerHTML = `
                        <input type="hidden" name="barang[]" value="${barang.id_barang}">
                        <div class="flex-1 relative">
                            <i class="fa-solid fa-boxes-stacked absolute left-3 top-3 text-kedua"></i>
                            <input value="${ucWords(barang.nama_barang)}" disabled class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                            <p class="text-sm text-kedua absolute bottom-3 right-6">Stok: ${barang.stok_barang}</p>
                        </div>
                        <div class="w-full lg:w-32 relative">
                            <i class="fa-solid fa-cubes absolute left-3 top-3 text-kedua"></i>
                            <input value="${barang.jumlah_barang}" name="jumlah[]" type="number" min="1" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 text-kedua rounded-lg hover:scale-105 transition">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                    container.appendChild(div);
                });
            });
        openModal('edit');
    }

    function showDelete(id, nama) {
        document.getElementById('namaDelete').innerText = nama;
        document.getElementById('formDelete').action = `/management/inventaris-ruangan/${id}`;
        openModal('hapus');
    }

    document.getElementById('btnTambah').addEventListener('click', () => {
        const container = document.getElementById('containerTambahanBarang');
        const div = document.createElement('div');
        div.className = 'flex flex-col md:flex-row gap-4 mb-4';
        div.innerHTML = `
            <div class="flex-1 relative">
                <i class="fa-solid fa-boxes-stacked absolute left-3 top-3 text-kedua"></i>
                <select name="barang[]" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                    <option value="" selected disabled>Pilih Barang</option>
                    @foreach($barang as $b)
                        <option value="{{ $b->id_barang }}" data-stok="{{ $b->stok_barang }}">{{ ucfirst($b->nama_barang) }}</option>
                    @endforeach
                </select>
                <p class="text-sm text-kedua absolute bottom-3 right-6 stok-teks"></p>
            </div>
            <div class="w-32 relative">
                <i class="fa-solid fa-cubes absolute left-3 top-3 text-kedua"></i>
                <input name="jumlah[]" type="number" min="1" placeholder="Jumlah" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 text-kedua rounded-lg hover:scale-105 transition">
                <i class="fa-solid fa-trash"></i>
            </button>
        `;
        container.appendChild(div);

        const select = div.querySelector('select');
        const stokText = div.querySelector('.stok-teks');

        select.addEventListener('change', function() {
            const stok = this.options[this.selectedIndex].dataset.stok;
            console.log(stok);
            stokText.innerText = `Stok: ${stok}`;
        });
    });


    document.getElementById('btnTambahEdit').addEventListener('click', () => {
        const container = document.getElementById('containerTambahanBarangEdit');
        const div = document.createElement('div');
        div.className = 'flex gap-4';
        div.innerHTML = `
            <div class="flex-1 relative">
                <i class="fa-solid fa-boxes-stacked absolute left-3 top-3 text-kedua"></i>
                <select name="barang[]" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                    <option value="" selected disabled>Pilih Barang</option>
                    @foreach($barang as $b)
                        <option value="{{ $b->id_barang }}" data-stok="{{ $b->stok_barang }}">{{ ucfirst($b->nama_barang) }}</option>
                    @endforeach
                </select>
                <p class="text-sm text-kedua absolute bottom-3 right-6 stok-teks"></p>
            </div>
            <div class="w-32 relative">
                <i class="fa-solid fa-cubes absolute left-3 top-3 text-kedua"></i>
                <input name="jumlah[]" type="number" min="1" placeholder="Jumlah" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-pendukung-1 text-kedua rounded-lg hover:scale-105 transition">
                <i class="fa-solid fa-trash"></i>
            </button>
        `;
        container.appendChild(div);

        const select = div.querySelector('select');
        const stokText = div.querySelector('.stok-teks');

        select.addEventListener('change', function() {
            const stok = this.options[this.selectedIndex].dataset.stok;
            stokText.innerText = `Stok: ${stok}`;
        });
    });

    function ucWords(str) {
        return str.replace(/\b\w/g, char => char.toUpperCase());
    }

    function formatTanggal(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function hapusRuangan(id, nama) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            html: `Data ruangan <strong>${nama}</strong> akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fa-solid fa-trash mr-2"></i>Hapus',
            cancelButtonText: '<i class="fa-solid fa-xmark mr-2"></i>Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/management/inventaris-barang-ruangan/${id}`;
                form.style.display = 'none';

                const token = document.createElement('input');
                token.name = '_token';
                token.value = '{{ csrf_token() }}';
                form.appendChild(token);

                const method = document.createElement('input');
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(method);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection