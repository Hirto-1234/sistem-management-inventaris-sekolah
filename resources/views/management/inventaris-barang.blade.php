@extends('layouts.admin')

@section('title', 'Inventaris Barang')

@section('content-admin')
<div class="p-6 font-utama">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-utama">
            <i class="fa-solid fa-box-archive mr-3"></i>Inventaris Barang
        </h1>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <a href="{{ route('management.inventaris-barang.print', request()->query()) }}" target="_blank" class="w-full sm:w-auto bg-ketiga text-kedua px-4 py-2 rounded-lg hover:scale-105 transition text-center">
                <i class="fa-solid fa-print mr-2"></i>Print PDF
            </a>
            <button onclick="openModal('tambah')" class="w-full sm:w-auto bg-ketiga text-utama px-4 py-2 rounded-lg hover:scale-105 transition">
                <i class="fa-solid fa-plus mr-2"></i>Tambah Barang
            </button>
        </div>
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
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap "><i class="fa-solid fa-hashtag mr-2"></i>ID</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap "><i class="fa-solid fa-image mr-2"></i>Foto</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-box mr-2"></i>Nama</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-layer-group mr-2"></i>Kategori</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-cubes mr-2"></i>Stok</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-center text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-cog mr-2"></i>Aksi</th>
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
                    <td class="px-2 sm:px-4 py-3 text-center whitespace-nowrap">
                        <button onclick="openDetail({{ $barang->id_barang }})" class="bg-blue-600 p-1.5 rounded hover:scale-110" title="Detail">
                            <i class="fa-solid fa-eye text-xs sm:text-sm"></i>
                        </button>
                        <button onclick="openEdit({{ $barang->id_barang }})" class="bg-yellow-500 p-1.5 rounded hover:scale-110 ml-2" title="Edit">
                            <i class="fa-solid fa-pen text-xs sm:text-sm"></i>
                        </button>
                        <button onclick="openQR('{{ $barang->kode_qr }}', '{{ $barang->nama_barang }}')" class="bg-gray-500 p-1.5 rounded hover:scale-110 ml-2" title="QR Code">
                            <i class="fa-solid fa-qrcode text-xs sm:text-sm"></i>
                        </button>
                        @if(auth()->user()->role_pengguna == 'admin')
                        <form action="{{ route('management.inventaris-barang.destroy', $barang->id_barang) }}" method="POST" class="inline" onsubmit="return confirmHapus(this)">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-500 p-1.5 rounded hover:scale-110 ml-2" title="Hapus">
                                <i class="fa-solid fa-trash text-xs sm:text-sm"></i>
                            </button>
                        </form>
                        @endif
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

    <div class="mt-4">{{ $barangs->appends(request()->query())->links() }}</div>

    <div id="overlay" class="hidden fixed inset-0 z-40 backdrop-blur-md" onclick="closeModal()"></div>

    <div id="modalTambah" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-2xl overflow-auto max-h-[90vh]">
            <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-plus mr-2"></i>Tambah Barang</h3>
            <form id="formTambah" action="{{ route('management.inventaris-barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4 mb-4">
                    <div class="relative">
                        <i class="fa-solid fa-box absolute left-3 top-3 text-kedua"></i>
                        <input name="nama_barang" placeholder="Nama Barang" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-barcode absolute left-3 top-3 text-kedua"></i>
                        <input name="kode_sku" placeholder="Kode SKU Barang" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-layer-group absolute left-3 top-3 text-kedua"></i>
                        <select name="id_kategori" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id_kategori }}">{{ ucfirst($kat->nama_kategori) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="relative">
                        <i class="fa-solid fa-circle-info absolute left-3 top-3 text-kedua"></i>
                        <textarea name="deskripsi_barang" placeholder="Deskripsi" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua" rows="3"></textarea>
                    </div>
                    <div class="h-48">
                        <input name="foto_barang" type="file" accept="image/*" id="fotoTambah" onchange="previewImage(this, 'previewImgTambah')" class="hidden">
                        <label for="fotoTambah" class="cursor-pointer flex items-center justify-center w-full h-full border-2 border-dashed border-kedua rounded-lg hover:bg-kedua transition overflow-hidden">
                            <div id="labelTambah" class="text-center">
                                <i class="fa-solid fa-cloud-arrow-up text-4xl text-kedua mb-2"></i>
                                <p class="text-sm font-semibold">Upload Foto Barang</p>
                                <p class="text-xs text-kedua">Klik untuk pilih file</p>
                            </div>
                            <img id="previewImgTambah" src="" alt="" class="hidden w-full h-full object-cover">
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-5 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition">
                        <i class="fa-solid fa-xmark mr-2"></i>Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-pendukung-3 text-kedua rounded-lg hover:scale-105 transition">
                        <i class="fa-solid fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEdit" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-2xl overflow-auto max-h-[90vh]">
            <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-pen mr-2"></i>Edit Barang</h3>
            <form id="formEdit" method="POST" enctype="multipart/form-data">
                @csrf @method('PATCH')
                <div class="space-y-4 mb-4">
                    <div class="relative">
                        <i class="fa-solid fa-box absolute left-3 top-3 text-kedua"></i>
                        <input id="namaBarangEdit" name="nama_barang" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua" placeholder="Nama Barang">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-circle-info absolute left-3 top-3 text-kedua"></i>
                        <textarea id="deskripsiEdit" name="deskripsi_barang" placeholder="Deskripsi" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua" rows="3"></textarea>
                    </div>
                    <div class="h-48">
                        <input name="foto_barang" type="file" accept="image/*" id="fotoEdit" onchange="previewImage(this, 'previewImgEdit')" class="hidden">
                        <label for="fotoEdit" class="cursor-pointer flex items-center justify-center w-full h-full border-2 border-dashed border-kedua rounded-lg hover:bg-kedua transition overflow-hidden">
                            <div id="labelEdit" class="text-center">
                                <i class="fa-solid fa-cloud-arrow-up text-4xl text-kedua mb-2"></i>
                                <p class="text-sm font-semibold">Upload Foto Barang</p>
                                <p class="text-xs text-kedua">Klik untuk pilih file (opsional)</p>
                            </div>
                            <img id="previewImgEdit" src="" alt="" class="hidden w-full h-full object-cover">
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-5 py-2 bg-red-500 text-kedua rounded-lg hover:scale-105 transition">
                        <i class="fa-solid fa-xmark mr-2"></i>Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-yellow-500 text-kedua rounded-lg hover:scale-105 transition">
                        <i class="fa-solid fa-save mr-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalDetail" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-4xl overflow-auto max-h-[90vh]">
            <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-list mr-2"></i>Detail Barang</h3>

            <div id="containerImg" class="mb-4 flex justify-center"></div>

            <div class="p-4 bg-kedua rounded-lg border border-kedua mb-4">
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                    <p class="text-sm"><i class="fa-solid fa-hashtag mr-2"></i><strong>ID:</strong> <span id="detailId" class="ml-2"></span></p>
                    <p class="text-sm"><i class="fa-solid fa-box mr-2"></i><strong>Nama:</strong> <span id="detailNama" class="ml-2"></span></p>
                    <p class="text-sm"><i class="fa-solid fa-barcode mr-2"></i><strong>Kode SKU:</strong> <span id="detailSku" class="ml-2"></span></p>
                    <p class="text-sm"><i class="fa-solid fa-layer-group mr-2"></i><strong>Kategori:</strong> <span id="detailKategori" class="ml-2"></span></p>
                    <p class="text-sm"><i class="fa-solid fa-cubes mr-2"></i><strong>Jumlah:</strong> <span id="detailJumlah" class="ml-2"></span></p>
                    <p class="text-sm"><i class="fa-solid fa-cubes mr-2"></i><strong>Stok:</strong> <span id="detailStok" class="ml-2"></span></p>
                    <p class="text-sm col-span-2"><i class="fa-solid fa-user-circle mr-2"></i><strong>Data diupdate oleh:</strong> <span id="detailPengguna" class="ml-2"></span></p>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition">
                    <i class="fa-solid fa-xmark mr-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>

    <div id="modalQR" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="flex flex-col items-center bg-utama text-kedua p-6 rounded-xl shadow-lg overflow-auto max-h-[90vh]">
            <h3 class="text-xl font-bold mb-4">
                <i class="fa-solid fa-qrcode mr-2"></i>QR CODE Barang <span id="qrCodeNamaBarang"></span>
            </h3>

            <div id="containerQR" class="p-4"></div>

            <div class="mt-4 flex items-center gap-4">
                 <button onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition">
                    <i class="fa-solid fa-xmark mr-2"></i>Tutup
                </button>
                <button onclick="downloadQR()" class="rounded-lg px-4 py-2 bg-green-500 text-kedua hover:scale-105 transition">
                    <i class="fa-solid fa-file-arrow-down mr-2"></i>Unduh
                </button>
            </div>
        </div>
    </div>

</div>

{{-- SCRIPT MODAL + AJAX --}}
<script>
    if (document.getElementById('swal-success')) {
        Swal.fire({
            icon: 'success',
            title: document.getElementById('swal-success').innerText,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
        });
    }
    if (document.getElementById('swal-error')) {
        Swal.fire({
            icon: 'error',
            title: document.getElementById('swal-error').innerText,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        let data = localStorage.getItem('qrData');
        if (data) {
            data = JSON.parse(data);
            openModal('detail', data.id_barang);
            localStorage.removeItem('qrData');
        }

        if (document.getElementById('swal-validation')) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: document.getElementById('swal-validation').innerHTML
            });
        }
    });

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    function openDetail(id) {
        openModal('detail', id);
    }

    function openQR(kode, nama) {
        openModal('qr', null, kode, nama);
    }

    function openEdit(id) {
        openModal('edit', id);
    }

    function openModal(type, id = null, kode = null, nama = null) {
        closeModal();
        document.getElementById('overlay').classList.remove('hidden');

        if (type === 'tambah') {
            document.getElementById('modalTambah').classList.remove('hidden');
        } else if (type === 'edit' && id) {
            const form = document.getElementById('formEdit');
            form.action = `/management/inventaris-barang/${id}`;
            
            // Fetch data barang dan populate form
            fetch(`/management/inventaris-barang/${id}`)
                .then(r => r.json())
                .then(data => {
                    // Akses data barang langsung (bukan data.barang)
                    document.getElementById('namaBarangEdit').value = ucWords(data.nama_barang);
                    document.getElementById('deskripsiEdit').value = data.deskripsi_barang || '';
                    
                    // Tampilkan foto lama jika ada
                    if (data.foto_barang) {
                        const img = document.getElementById('previewImgEdit');
                        const label = document.getElementById('labelEdit');
                        img.src = `/uploads/barangs/${data.foto_barang}`;
                        img.classList.remove('hidden');
                        label.classList.add('hidden');
                    } else {
                        resetPreview('previewImgEdit');
                    }
                    
                    // Tampilkan modal setelah data dimuat
                    document.getElementById('modalEdit').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal memuat data',
                        text: 'Terjadi kesalahan saat mengambil data barang'
                    });
                });
        } else if (type === 'detail' && id) {
            fetch(`/management/inventaris-barang/${id}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('detailId').textContent = data.id_barang;
                    document.getElementById('detailNama').textContent = ucWords(data.nama_barang);
                    document.getElementById('detailSku').textContent = data.sku_barang || '-';
                    document.getElementById('detailKategori').textContent = ucWords(data.kategori?.nama_kategori || '-');
                    document.getElementById('detailJumlah').textContent = data.jumlah_barang;
                    document.getElementById('detailStok').textContent = data.stok_barang;
                    document.getElementById('detailPengguna').textContent = data.diupdate_oleh || '-';

                    const imgContainer = document.getElementById('containerImg');
                    imgContainer.innerHTML = data.foto_barang
                        ? `<img src="/uploads/barangs/${data.foto_barang}"
                                alt="${data.nama_barang}"
                                class="w-full max-h-[300px] object-contain">`
                        : '<div class="w-full h-[250px] bg-gray-200 flex items-center justify-center"><i class="fa-solid fa-image text-6xl text-gray-400"></i></div>';

                    document.getElementById('modalDetail').classList.remove('hidden');
                });
        } else if (type == 'qr') {
            document.getElementById('modalQR').classList.remove('hidden');
            document.getElementById('qrCodeNamaBarang').innerText = ucWords(nama);
            document.getElementById('containerQR').innerHTML = ``;
            let url = `http://127.0.0.1:8000/verifikasi/${kode}`;
            new QRCode(document.getElementById('containerQR'), {
                text: url,
                width:  180,
                height: 180
            });
        }
    }

    function closeModal() {
        document.getElementById('overlay').classList.add('hidden');
        ['modalTambah', 'modalEdit', 'modalDetail', 'modalQR'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
        
        });
        
        // Reset semua preview saat modal ditutup
        resetPreview('previewImgTambah');
        resetPreview('previewImgEdit');
    }

    function previewImage(input, previewId) {
        const img = document.getElementById(previewId);
        const label = document.getElementById('label' + previewId.replace('previewImg', ''));
        
        if (input.files?.[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                img.src = e.target.result;
                img.classList.remove('hidden');
                label.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            resetPreview(previewId);
        }
    }

    function resetPreview(previewId) {
        const img = document.getElementById(previewId);
        const label = document.getElementById('label' + previewId.replace('previewImg', ''));
        const input = document.getElementById('foto' + previewId.replace('previewImg', ''));
        
        if (img && label) {
            img.src = '';
            img.classList.add('hidden');
            label.classList.remove('hidden');
        }
        
        if (input) {
            input.value = '';
        }
    }

    function ucWords(str) {
        if (!str) return '';
        return str.replace(/\b\w/g, char => char.toUpperCase());
    }

    function downloadQR() {
        const container = document.getElementById('containerQR');
        const img = container.querySelector('img');
        const canvas = container.querySelector('canvas');

        if (img) {
            const a = document.createElement('a');
            a.href = img.src;
            a.download = 'qr-code.png';
            a.click();
            return;
        }

        if (canvas) {
            const a = document.createElement('a');
            a.href = canvas.toDataURL('image/png');
            a.download = 'qr-code.png';
            a.click();
            return;
        }

        console.error("QR code tidak ditemukan di dalam #containerQR");
    }

    function confirmHapus(form) {
        Swal.fire({
            title: 'Hapus Barang?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }
</script>
@endsection