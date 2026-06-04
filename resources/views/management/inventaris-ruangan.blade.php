@extends('layouts.admin')

@section('title', 'Inventaris Ruangan')

@section('content-admin')
<div class="p-6 font-utama">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-xl font-bold text-utama"><i class="fa-solid fa-door-open mr-3"></i>Inventaris Ruangan</h1>
        <button onclick="bukaModal('modalTambah')" class="w-full sm:w-auto bg-ketiga text-utama px-4 py-2 rounded-lg hover:scale-105 transition">
            <i class="fa-solid fa-plus mr-2"></i>Tambah Ruangan
        </button>
    </div>

    <form method="GET" class="mb-6 bg-utama p-4 rounded-lg border border-kedua shadow-md flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fa-solid fa-search absolute left-3 top-3 text-kedua"></i>
            <input type="text" name="search" value="{{request('search')}}" placeholder="Cari ruangan..." class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
        </div>
        <div class="w-full sm:w-48 relative">
            <i class="fa-solid fa-filter absolute left-3 top-3 text-kedua"></i>
            <select name="status_penggunaan" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                <option disabled selected value="">Semua Status</option>
                <option value="digunakan" {{request('status_penggunaan')=='digunakan'?'selected':''}}>Digunakan</option>
                <option value="tidak_digunakan" {{request('status_penggunaan')=='tidak_digunakan'?'selected':''}}>Tidak Digunakan</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-kedua text-utama rounded-lg hover:scale-105 transition"><i class="fa-solid fa-filter mr-2"></i>Filter</button>
        <a href="{{route('management.inventaris-ruangan.index')}}" class="px-4 py-2 bg-gray-400 text-kedua rounded-lg hover:scale-105 transition text-center"><i class="fa-solid fa-rotate-right mr-2"></i>Reset</a>
    </form>

    <div class="overflow-x-auto border border-kedua rounded-lg shadow-md bg-utama">
        <table class="w-full text-sm text-kedua">
            <thead class="bg-ketiga border-b border-kedua">
                <tr>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-hashtag mr-2"></i>ID</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-door-open mr-2"></i>Nama Ruangan</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-box mr-2"></i>Jumlah Inventaris</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-center text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-cog mr-2"></i>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $ruangan)
                    <tr class="border-b border-kedua hover:bg-kedua transition">
                        <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-hashtag mr-2"></i>{{$ruangan->id_ruangan}}</td>
                        <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm"><i class="fa-solid fa-door-open mr-2"></i>{{$ruangan->nama_ruangan}}</td>
                        <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap hidden md:table-cell">
                            <span class="px-2 py-1 rounded text-xs {{$ruangan->inventaris_ruangan_count > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}}">
                                <i class="fa-solid fa-box mr-1"></i>{{$ruangan->inventaris_ruangan_count}} inventaris
                            </span>
                        </td>
                        <td class="px-2 sm:px-4 py-3 text-center whitespace-nowrap">
                            <button onclick="bukaModal('modalDetail{{$ruangan->id_ruangan}}')" class="bg-blue-600 rounded p-1.5 hover:scale-110" title="Detail">
                                <i class="fa-solid fa-eye text-xs sm:text-sm"></i>
                            </button>
                            @if($ruangan->nama_ruangan !== 'TU')
                                <button onclick="bukaModalEdit({{$ruangan->id_ruangan}}, '{{addslashes($ruangan->nama_ruangan)}}', '{{addslashes($ruangan->deskripsi_ruangan ?? '')}}')" class="bg-yellow-500 p-1.5 rounded hover:scale-110 ml-2" title="Edit">
                                    <i class="fa-solid fa-pen text-xs sm:text-sm"></i>
                                </button>
                                <button onclick="konfirmasiHapus({{$ruangan->id_ruangan}}, '{{addslashes($ruangan->nama_ruangan)}}')" class="bg-red-500 p-1.5 rounded hover:scale-110 ml-2" title="Hapus">
                                    <i class="fa-solid fa-trash text-xs sm:text-sm"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-kedua text-xs sm:text-sm">
                            <i class="fa-solid fa-inbox mr-2"></i>Belum ada data ruangan
                        </td>
                    </tr>
                @endforelse
            </tbody> 
        </table>
    </div>

    <div class="mt-4">{{ $data->appends(request()->query())->links() }}</div>

    <div id="overlay" class="hidden fixed inset-0 z-40 backdrop-blur-md" onclick="tutupSemua()"></div>

    @foreach($data as $ruangan)
        <div id="modalDetail{{$ruangan->id_ruangan}}" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
            <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-2xl">
                <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-list mr-2"></i>Detail Ruangan</h3>
                <div class="space-y-3 mb-4">
                    <div class="p-4 bg-kedua rounded-lg border border-kedua">
                        <p class="text-sm mb-2"><i class="fa-solid fa-hashtag mr-2"></i><strong>ID Ruangan:</strong> {{$ruangan->id_ruangan}}</p>
                        <p class="text-sm mb-2"><i class="fa-solid fa-door-open mr-2"></i><strong>Nama Ruangan:</strong> {{$ruangan->nama_ruangan}}</p>
                        <p class="text-sm mb-2"><i class="fa-solid fa-comment mr-2"></i><strong>Deskripsi:</strong> {{$ruangan->deskripsi_ruangan ?? '-'}}</p>
                        <p class="text-sm"><i class="fa-solid fa-box mr-2"></i><strong>Jumlah Inventaris:</strong> <span class="px-2 py-1 rounded text-xs {{$ruangan->inventaris_ruangan_count > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}}">{{$ruangan->inventaris_ruangan_count}} inventaris</span></p>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button onclick="tutupSemua()" class="px-5 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition"><i class="fa-solid fa-xmark mr-2"></i>Tutup</button>
                </div>
            </div>
        </div>
    @endforeach

    <div id="modalTambah" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-md">
            <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-plus mr-2"></i>Tambah Ruangan</h3>
            <form action="{{route('management.inventaris-ruangan.store')}}" method="POST">
                @csrf
                <div class="space-y-4 mb-4">
                    <div class="relative"><i class="fa-solid fa-door-open absolute left-3 top-3 text-kedua"></i><input type="text" name="nama_ruangan" required maxlength="255" placeholder="Nama Ruangan" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua"></div>
                    <div class="relative"><i class="fa-solid fa-comment absolute left-3 top-3 text-kedua"></i><textarea name="deskripsi_ruangan" rows="3" placeholder="Deskripsi (Opsional)" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua"></textarea></div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="tutupSemua()" class="px-5 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition"><i class="fa-solid fa-xmark mr-2"></i>Batal</button>
                    <button type="submit" class="px-5 py-2 bg-green-500 text-kedua rounded-lg hover:scale-105 transition"><i class="fa-solid fa-save mr-2"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEdit" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-md">
            <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-pen mr-2"></i>Edit Ruangan</h3>
            <form id="formEdit" method="POST">
                @csrf @method('PUT')
                <div class="space-y-4 mb-4">
                    <div class="relative"><i class="fa-solid fa-door-open absolute left-3 top-3 text-kedua"></i><input id="editNama" type="text" name="nama_ruangan" required maxlength="255" placeholder="Nama Ruangan" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua"></div>
                    <div class="relative"><i class="fa-solid fa-comment absolute left-3 top-3 text-kedua"></i><textarea id="editDeskripsi" name="deskripsi_ruangan" rows="3" placeholder="Deskripsi (Opsional)" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua"></textarea></div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="tutupSemua()" class="px-5 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition"><i class="fa-solid fa-xmark mr-2"></i>Batal</button>
                    <button type="submit" class="px-5 py-2 bg-yellow-500 text-kedua rounded-lg hover:scale-105 transition"><i class="fa-solid fa-save mr-2"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
setTimeout(() => document.getElementById('pesan')?.remove(), 5000);
document.addEventListener('keydown', e => e.key === 'Escape' && tutupSemua());

function bukaModal(idModal) {
    tutupSemua();
    document.getElementById('overlay').classList.remove('hidden');
    document.getElementById(idModal).classList.remove('hidden');
}

function tutupSemua() {
    document.querySelectorAll('[id^="modal"]').forEach(modal => modal.classList.add('hidden'));
    document.getElementById('overlay').classList.add('hidden');
}

function bukaModalEdit(id, nama, deskripsi) {
    document.getElementById('formEdit').action = `/management/inventaris-ruangan/${id}`;
    document.getElementById('editNama').value = nama;
    document.getElementById('editDeskripsi').value = deskripsi || '';
    bukaModal('modalEdit');
}

function konfirmasiHapus(id, nama) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Ruangan "${nama}" akan dihapus permanen!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        background: '#1E3A8A',
        color: '#FFFFFF',
        confirmButtonText: '<i class="fa-solid fa-trash mr-2"></i>Hapus',
        cancelButtonText: '<i class="fa-solid fa-xmark mr-2"></i>Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const formHapus = document.createElement('form');
            formHapus.method = 'POST';
            formHapus.action = "{{ url('management/inventaris-ruangan') }}/" + id;
            formHapus.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(formHapus);
            formHapus.submit();
        }
    });
}
</script>
@endsection