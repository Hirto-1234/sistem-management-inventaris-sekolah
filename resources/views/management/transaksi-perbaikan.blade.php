@extends('layouts.admin')

@section('title', 'Transaksi Perbaikan Barang')

@section('content-admin')
<div class="p-6 font-utama">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-utama"><i class="fa-solid fa-screwdriver-wrench mr-3"></i>Transaksi Perbaikan</h1>
        <button onclick="bukaModal('modalTambah')" class="w-full sm:w-auto bg-ketiga text-utama px-4 py-2 rounded-lg hover:scale-105 transition">
            <i class="fa-solid fa-plus mr-2"></i>Tambah Perbaikan
        </button>
    </div>

    <form method="GET" class="mb-6 bg-utama p-4 rounded-lg border border-kedua shadow-md flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fa-solid fa-search absolute left-3 top-3 text-kedua"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi kerusakan..." class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
        </div>
        <div class="w-full sm:w-48 relative">
            <i class="fa-solid fa-circle-info absolute left-3 top-3 text-kedua"></i>
            <select name="status" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                <option disabled selected value="">Semua Status</option>
                <option value="pending" {{request('status')=='pending'?'selected':''}}>Pending</option>
                <option value="diproses" {{request('status')=='diproses'?'selected':''}}>Diproses</option>
                <option value="berhasil" {{request('status')=='berhasil'?'selected':''}}>Berhasil</option>
                <option value="gagal" {{request('status')=='gagal'?'selected':''}}>Gagal</option>
                <option value="dibatalkan" {{request('status')=='dibatalkan'?'selected':''}}>Dibatalkan</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-kedua text-utama rounded-lg hover:scale-105 transition"><i class="fa-solid fa-filter mr-2"></i>Filter</button>
        <a href="{{route('management.transaksi-perbaikan.index')}}" class="px-4 py-2 bg-gray-400 text-kedua rounded-lg hover:scale-105 transition text-center"><i class="fa-solid fa-rotate-right mr-2"></i>Reset</a>
    </form>

    <div class="overflow-x-auto border border-kedua rounded-lg shadow-md bg-utama">
        <table class="w-full text-sm text-kedua">
            <thead class="bg-ketiga border-b border-kedua">
                <tr>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-hashtag mr-2"></i>ID</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-box mr-2"></i>Barang</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-door-open mr-2"></i>Ruangan</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-cubes mr-2"></i>Jumlah</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-circle-info mr-2"></i>Status</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-center text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-cog mr-2"></i>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perbaikan as $item)
                    <tr class="border-b border-kedua hover:bg-kedua transition">
                        <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-hashtag mr-2"></i>{{$item->id_perbaikan}}</td>
                        <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-box mr-2"></i>{{$item->inventarisRuangan->barang->nama_barang??'-'}}</td>
                        <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-door-open mr-2"></i>{{$item->inventarisRuangan->ruangan->nama_ruangan??'-'}}</td>
                        <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-cubes mr-2"></i>{{$item->jumlah_barang}}</td>
                        <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap">
                            @php 
                                $badges = [
                                    'berhasil' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'fa-circle-check'],
                                    'diproses' => ['class' => 'bg-blue-100 text-blue-800', 'icon' => 'fa-spinner'],
                                    'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fa-hourglass-half'],
                                    'gagal' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'fa-circle-xmark'],
                                    'dibatalkan' => ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-ban']
                                ];
                                $badge = $badges[$item->status] ?? ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-circle-info'];
                            @endphp
                            <span class="px-2 py-1 rounded text-xs {{$badge['class']}}">
                                <i class="fa-solid {{$badge['icon']}} mr-1"></i>{{ucfirst($item->status)}}
                            </span>
                        </td>
                        <td class="px-2 sm:px-4 py-3 text-center whitespace-nowrap">
                            <button onclick="bukaModal('modalDetail{{$item->id_perbaikan}}')" class="bg-blue-600 p-1.5 rounded hover:scale-110" title="Detail"><i class="fa-solid fa-eye text-xs sm:text-sm"></i></button>
                            @if(!in_array($item->status, ['berhasil', 'gagal', 'dibatalkan']))
                                <button onclick="bukaModal('modalStatus{{$item->id_perbaikan}}')" class="bg-yellow-500 p-1.5 rounded hover:scale-110 ml-2" title="Update"><i class="fa-solid fa-rotate text-xs sm:text-sm"></i></button>
                            @endif
                            @if(in_array($item->status, ['berhasil', 'gagal', 'dibatalkan']))
                                <button onclick="konfirmasiHapus({{$item->id_perbaikan}})" class="bg-red-500 p-1.5 rounded hover:scale-110 ml-2" title="Hapus"><i class="fa-solid fa-trash text-xs sm:text-sm"></i></button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-4 text-center text-kedua text-xs sm:text-sm"><i class="fa-solid fa-inbox mr-2"></i>Belum ada data perbaikan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $perbaikan->appends(request()->query())->links() }}</div>
    
    <div id="overlay" class="hidden fixed inset-0 z-40 backdrop-blur-md" onclick="tutupSemua()"></div>

    <div id="modalTambah" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-4xl overflow-auto max-h-[90vh]">
            <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-plus mr-2"></i>Tambah Perbaikan</h3>
            <form action="{{route('management.transaksi-perbaikan.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="relative">
                        <i class="fa-solid fa-door-open absolute left-3 top-3 text-kedua"></i>
                        <select id="pilihRuangan" onchange="muatBarang()" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama" required>
                            <option disabled selected value="">Pilih Ruangan</option>
                            @foreach($inventaris->groupBy('id_ruangan') as $idRuangan => $items)
                                <option value="{{$idRuangan}}">{{$items[0]->ruangan->nama_ruangan??'-'}}</option>
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
                        <i class="fa-solid fa-calendar absolute left-3 top-3 text-kedua"></i>
                        <input type="datetime-local" name="tanggal_mulai" value="{{ old('tanggal_mulai', now()->timezone('Asia/Jakarta')->format('Y-m-d\TH:i')) }}" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama" required>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="relative h-48">
                        <i class="fa-solid fa-comment absolute left-3 top-3 text-kedua z-10"></i>
                        <textarea name="deskripsi_kerusakan" rows="6" placeholder="Deskripsi Kerusakan" class="w-full h-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua" required></textarea>
                    </div>
                    <div class="h-48">
                        <input type="file" accept="image/*" name="foto_sebelum" id="fotoSebelum" onchange="preview(this, 'prevSebelum')" class="hidden" required>
                        <label for="fotoSebelum" class="cursor-pointer flex items-center justify-center w-full h-full border-2 border-dashed border-kedua rounded-lg hover:bg-kedua transition overflow-hidden">
                            <div id="labelSebelum" class="text-center">
                                <i class="fa-solid fa-cloud-arrow-up text-4xl text-kedua mb-2"></i>
                                <p class="text-sm font-semibold">Upload Foto Sebelum</p>
                                <p class="text-xs text-kedua">Klik untuk pilih file</p>
                            </div>
                            <img id="prevSebelum" src="" class="hidden w-full h-full object-cover">
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" onclick="tutupSemua()" class="px-5 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition"><i class="fa-solid fa-xmark mr-2"></i>Batal</button>
                    <button type="submit" class="px-5 py-2 bg-green-500 text-kedua rounded-lg hover:scale-105 transition"><i class="fa-solid fa-save mr-2"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @foreach($perbaikan as $item)
        <div id="modalDetail{{$item->id_perbaikan}}" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
            <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-4xl overflow-auto max-h-[90vh]">
                <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-list mr-2"></i>Detail Perbaikan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="p-4 bg-kedua rounded-lg border border-kedua">
                        <p class="text-sm mb-2"><i class="fa-solid fa-qrcode mr-2"></i><strong>ID:</strong> {{$item->id_perbaikan}}</p>
                        <p class="text-sm mb-2"><i class="fa-solid fa-user-circle mr-2"></i><strong>Diinput Oleh:</strong> {{$item->pengguna->nama_pengguna??'-'}}</p>
                        <p class="text-sm mb-2"><i class="fa-solid fa-box mr-2"></i><strong>Barang:</strong> {{$item->inventarisRuangan->barang->nama_barang??'-'}}</p>
                        <p class="text-sm mb-2"><i class="fa-solid fa-door-open mr-2"></i><strong>Ruangan:</strong> {{$item->inventarisRuangan->ruangan->nama_ruangan??'-'}}</p>
                        <p class="text-sm mb-2"><i class="fa-solid fa-cubes mr-2"></i><strong>Jumlah:</strong> {{$item->jumlah_barang}}</p>
                        <p class="text-sm mb-2"><i class="fa-solid fa-calendar mr-2"></i><strong>Tanggal Mulai:</strong> {{\Carbon\Carbon::parse($item->tanggal_mulai)->format('d F Y H:i')}}</p>
                        <p class="text-sm mb-2"><i class="fa-solid fa-calendar-check mr-2"></i><strong>Tanggal Selesai:</strong> {{$item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d F Y H:i') : '-'}}</p>
                        <p class="text-sm"><i class="fa-solid fa-circle-info mr-2"></i><strong>Status:</strong>
                            @php 
                                $badges = [
                                    'berhasil' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'fa-circle-check'],
                                    'diproses' => ['class' => 'bg-blue-100 text-blue-800', 'icon' => 'fa-spinner'],
                                    'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fa-hourglass-half'],
                                    'gagal' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'fa-circle-xmark'],
                                    'dibatalkan' => ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-ban']
                                ];
                                $badge = $badges[$item->status] ?? ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-circle-info'];
                            @endphp
                            <span class="px-2 py-1 rounded text-xs {{$badge['class']}}">
                                <i class="fa-solid {{$badge['icon']}} mr-1"></i>{{ucfirst($item->status)}}
                            </span>
                        </p>
                    </div>
                    <div class="p-4 bg-kedua rounded-lg border border-kedua">
                        <p class="text-sm mb-2"><i class="fa-solid fa-comment mr-2"></i><strong>Deskripsi Kerusakan:</strong></p>
                        <p class="text-sm mb-4">{{$item->deskripsi_kerusakan??'-'}}</p>
                        
                        <p class="text-sm mb-2"><i class="fa-solid fa-tools mr-2"></i><strong>Deskripsi Perbaikan:</strong></p>
                        <p class="text-sm mb-4">{{$item->deskripsi_perbaikan??'-'}}</p>
                        
                        <p class="text-sm mb-2"><i class="fa-solid fa-note-sticky mr-2"></i><strong>Catatan:</strong></p>
                        <p class="text-sm">{{$item->catatan??'-'}}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm mb-2 font-semibold"><i class="fa-solid fa-camera mr-1"></i>Foto Sebelum Perbaikan</p>
                        @if($item->foto_sebelum)
                            <a href="{{asset($item->foto_sebelum)}}" target="_blank">
                                <img src="{{asset($item->foto_sebelum)}}" class="w-full h-48 object-cover rounded-lg border hover:scale-105 transition">
                            </a>
                        @else
                            <div class="w-full h-48 flex items-center justify-center bg-kedua rounded-lg border">
                                <i class="fa-solid fa-image text-4xl text-kedua"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm mb-2 font-semibold"><i class="fa-solid fa-camera mr-1"></i>Foto Sesudah Perbaikan</p>
                        @if($item->foto_sesudah)
                            <a href="{{asset($item->foto_sesudah)}}" target="_blank">
                                <img src="{{asset($item->foto_sesudah)}}" class="w-full h-48 object-cover rounded-lg border hover:scale-105 transition">
                            </a>
                        @else
                            <div class="w-full h-48 flex items-center justify-center bg-kedua rounded-lg border">
                                <i class="fa-solid fa-image text-4xl text-kedua"></i>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button onclick="tutupSemua()" class="px-4 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition"><i class="fa-solid fa-xmark mr-2"></i>Tutup</button>
                </div>
            </div>
        </div>
    @endforeach

    @foreach($perbaikan as $item)
        <div id="modalStatus{{$item->id_perbaikan}}" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
            <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-2xl overflow-auto max-h-[90vh]">
                <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-rotate mr-2"></i>Update Status Perbaikan</h3>
                <form action="{{url('management/transaksi-perbaikan/' . $item->id_perbaikan)}}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="space-y-4 mb-4">
                        <div class="relative">
                            <i class="fa-solid fa-circle-info absolute left-3 top-3 text-kedua"></i>
                            <select name="status" id="selectStatus{{$item->id_perbaikan}}" onchange="toggleExtra({{$item->id_perbaikan}})" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama" required>
                                <option disabled selected value="">Pilih Status</option>
                                @if($item->status == 'pending')
                                    <option value="diproses">Diproses</option>
                                @endif
                                @if($item->status == 'diproses')
                                    <option value="berhasil">Berhasil</option>
                                    <option value="gagal">Gagal</option>
                                @endif
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-note-sticky absolute left-3 top-3 text-kedua"></i>
                            <textarea name="catatan" rows="3" placeholder="Catatan (opsional)" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua"></textarea>
                        </div>
                        <div id="extraFields{{$item->id_perbaikan}}" class="hidden space-y-4">
                            <div class="relative">
                                <i class="fa-solid fa-calendar-check absolute left-3 top-3 text-kedua"></i>
                                <input type="datetime-local" name="tanggal_selesai" value="{{ old('tanggal_selesai', now()->timezone('Asia/Jakarta')->format('Y-m-d\TH:i')) }}" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                            </div>
                            <div class="relative">
                                <i class="fa-solid fa-tools absolute left-3 top-3 text-kedua"></i>
                                <textarea name="deskripsi_perbaikan" rows="4" placeholder="Deskripsi Perbaikan" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua"></textarea>
                            </div>
                            <div class="h-48">
                                <input type="file" accept="image/*" name="foto_sesudah" id="fotoSesudah{{$item->id_perbaikan}}" onchange="preview(this, 'prevSesudah{{$item->id_perbaikan}}')" class="hidden">
                                <label for="fotoSesudah{{$item->id_perbaikan}}" class="cursor-pointer flex items-center justify-center w-full h-full border-2 border-dashed border-kedua rounded-lg hover:bg-kedua transition overflow-hidden">
                                    <div id="labelSesudah{{$item->id_perbaikan}}" class="text-center">
                                        <i class="fa-solid fa-cloud-arrow-up text-4xl text-kedua mb-2"></i>
                                        <p class="text-sm font-semibold">Upload Foto Sesudah</p>
                                        <p class="text-xs text-kedua">Klik untuk pilih file</p>
                                    </div>
                                    <img id="prevSesudah{{$item->id_perbaikan}}" src="" class="hidden w-full h-full object-cover">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="tutupSemua()" class="px-5 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition"><i class="fa-solid fa-xmark mr-2"></i>Batal</button>
                        <button type="submit" class="px-5 py-2 bg-yellow-500 text-kedua rounded-lg hover:scale-105 transition"><i class="fa-solid fa-save mr-2"></i>Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</div>

<script>
const dataInventaris = {
    @foreach($inventaris->groupBy('id_ruangan') as $idRuangan => $items)
        {{$idRuangan}}: [{!!$items->map(fn($inv)=>"{id:{$inv->id_inventaris_ruangan},nama:\"{$inv->barang->nama_barang}\",stok:{$inv->jumlah_barang}}")->implode(',')!!}],
    @endforeach
};

document.addEventListener('keydown', e => e.key === 'Escape' && tutupSemua());

function bukaModal(id) {
    tutupSemua();
    document.getElementById('overlay').classList.remove('hidden');
    document.getElementById(id).classList.remove('hidden');
}

function tutupSemua() {
    document.querySelectorAll('[id^="modal"]').forEach(m => m.classList.add('hidden'));
    document.getElementById('overlay').classList.add('hidden');
    ['prevSebelum', 'prevSesudah'].forEach(resetPreview);
}

function muatBarang() {
    const ruangan = document.getElementById('pilihRuangan').value;
    const selectBarang = document.getElementById('pilihBarang');
    
    if (!ruangan || !dataInventaris[ruangan]) {
        selectBarang.innerHTML = '<option disabled selected value="">Pilih Ruangan Dulu</option>';
        return;
    }
    
    selectBarang.innerHTML = '<option disabled selected value="">Pilih Barang</option>' + 
        dataInventaris[ruangan].map(b => `<option value="${b.id}">${b.nama} (Stok: ${b.stok})</option>`).join('');
}

function toggleExtra(id) {
    const status = document.getElementById('selectStatus' + id).value;
    const extra = document.getElementById('extraFields' + id);
    status === 'berhasil' || status === 'gagal' ? extra.classList.remove('hidden') : extra.classList.add('hidden');
}

function konfirmasiHapus(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Data perbaikan akan dihapus permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fa-solid fa-trash mr-2"></i>Hapus',
        cancelButtonText: '<i class="fa-solid fa-xmark mr-2"></i>Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{url('management/transaksi-perbaikan')}}/" + id;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function preview(input, previewId) {
    const img = document.getElementById(previewId);
    const label = document.getElementById('label' + previewId.replace('prev', ''));
    
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
    const label = document.getElementById('label' + previewId.replace('prev', ''));
    if (img && label) {
        img.src = '';
        img.classList.add('hidden');
        label.classList.remove('hidden');
    }
}
</script>
@endsection