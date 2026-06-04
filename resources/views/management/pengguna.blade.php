@extends('layouts.admin')

@section('title', 'Data Pengguna')

@section('content-admin')
<div class="p-6 font-utama">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-utama">
            <i class="fa-solid fa-users mr-3"></i>Data Pengguna
        </h1>
        <button onclick="openModal('tambah')" class="w-full sm:w-auto bg-ketiga text-utama px-4 py-2 rounded-lg hover:scale-105 transition">
            <i class="fa-solid fa-plus mr-2"></i>Tambah Pengguna
        </button>
    </div>

    <form method="GET" class="mb-6 bg-utama p-4 rounded-lg border border-kedua shadow-md flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fa-solid fa-search absolute left-3 top-3 text-kedua"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pengguna atau nama lengkap..." class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
        </div>
        <div class="w-full sm:w-48 relative">
            <i class="fa-solid fa-user-tag absolute left-3 top-3 text-kedua"></i>
            <select name="role" class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-kedua text-utama rounded-lg hover:scale-105 transition">
            <i class="fa-solid fa-filter mr-2"></i>Filter
        </button>
        <a href="{{ route('management.pengguna.index') }}" class="px-4 py-2 bg-gray-400 text-kedua rounded-lg hover:scale-105 transition">
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
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-user mr-2"></i>Username</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-left text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-id-card mr-2"></i>Nama Lengkap</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-center text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-user-tag mr-2"></i>Role</th>
                    <th class="text-utama px-2 sm:px-4 py-3 text-center text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-cog mr-2"></i>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $pengguna)
                <tr class="border-b border-kedua hover:bg-kedua transition">
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-hashtag mr-2"></i>{{ $pengguna->id_pengguna }}</td>
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap"><i class="fa-solid fa-user mr-2"></i>{{ $pengguna->nama_pengguna }}</td>
                    <td class="px-2 sm:px-4 py-3 text-xs sm:text-sm whitespace-nowrap hidden md:table-cell"><i class="fa-solid fa-id-card mr-2"></i>{{ $pengguna->nama_lengkap }}</td>
                    <td class="px-2 sm:px-4 py-3 text-center whitespace-nowrap hidden md:table-cell"><span class="px-2 py-1 rounded text-xs {{ $pengguna->role_pengguna == 'admin' ? 'bg-green-100 text-green-800' : ($pengguna->role_pengguna == 'petugas' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}"><i class="fa-solid {{ $pengguna->role_pengguna == 'admin' ? 'fa-crown text-green-500' : ($pengguna->role_pengguna == 'petugas' ? 'fa-id-card text-blue-800' : 'fa-user text-gray-800') }}"></i> {{ ucfirst($pengguna->role_pengguna) }}</span></td>
                    <td class="px-2 sm:px-4 py-3 text-center whitespace-nowrap">
                        <button data-pengguna='@json($pengguna)' onclick="showDetail(JSON.parse(this.dataset.pengguna))" class="bg-blue-600 p-1.5 rounded hover:scale-110" title="Detail">
                            <i class="fa-solid fa-eye text-xs sm:text-sm"></i>
                        </button>
                        <button onclick="showEdit('{{ $pengguna->id_pengguna }}', '{{ addslashes($pengguna->nama_lengkap) }}', '{{ $pengguna->nama_pengguna }}', '{{ $pengguna->role_pengguna }}', '{{ $pengguna->foto_pengguna }}')" class="bg-yellow-500 p-1.5 rounded hover:scale-110 ml-2" title="Edit">
                            <i class="fa-solid fa-pen text-xs sm:text-sm"></i>
                        </button>
                        <button onclick="hapusPengguna('{{ $pengguna->id_pengguna }}', '{{ $pengguna->nama_pengguna }}')" class="bg-red-500 p-1.5 rounded hover:scale-110 ml-2" title="Hapus">
                            <i class="fa-solid fa-trash text-xs sm:text-sm"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-kedua text-xs sm:text-sm">
                        <i class="fa-solid fa-inbox mr-2"></i>Belum ada data pengguna
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $data->appends(request()->query())->links() }}</div>

    <div id="overlay" class="hidden fixed inset-0 z-40 backdrop-blur-md" onclick="closeModal()"></div>
    
    <div id="modalTambah" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-4xl overflow-auto max-h-[90vh]">
            <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-user-plus mr-2"></i>Tambah Pengguna</h3>
            <form action="{{ route('management.pengguna.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="flex justify-center mb-4">
                    <label for="fotoTambah" class="cursor-pointer group">
                        <div class="relative w-32 h-32 rounded-full overflow-hidden border-4 border-kedua shadow-md transition-all group-hover:scale-105">
                            <img src="" id="previewTambah" class="hidden w-full h-full object-cover">
                            <div id="defaultViewTambah" class="w-full h-full bg-kedua/10 flex items-center justify-center">
                                <i class="fa-solid fa-camera text-3xl text-kedua/50"></i>
                            </div>
                        </div>
                        <input id="fotoTambah" type="file" name="foto_pengguna" accept="image/*" class="hidden">
                    </label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3 top-3 text-kedua"></i>
                        <input type="text" name="nama_pengguna" placeholder="Nama Pengguna" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-id-card absolute left-3 top-3 text-kedua"></i>
                        <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-3 top-3 text-kedua"></i>
                        <input type="text" name="email" placeholder="Email Pengguna" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3 top-3 text-kedua"></i>
                        <input type="password" name="password_pengguna" placeholder="Password" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-user-tag absolute left-3 top-3 text-kedua"></i>
                        <select name="role_pengguna" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-5 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition">
                        <i class="fa-solid fa-xmark mr-2"></i>Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-green-500 rounded-lg hover:scale-105 transition">
                        <i class="fa-solid fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalDetail" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-2xl overflow-auto max-h-[90vh]">
            <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-circle-info mr-2"></i>Detail Pengguna</h3>
            
            <div class="flex justify-center mb-4">
                <div id="containerImg"></div>
            </div>

            <div class="p-4 bg-kedua rounded-lg border border-kedua mb-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-x-2">
                        <i class="fa-solid fa-hashtag fa-2xl text-gray-500"></i>
                        <div>
                            <p class="text-sm">ID Pengguna :</p>
                            <p class="text-sm" id="detailId"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-x-2">
                        <i class="fa-solid fa-user fa-2xl text-indigo-500"></i>
                        <div>
                            <p class="text-sm">Nama Pengguna :</p>
                            <p class="text-sm" id="detailNama"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-x-2">
                        <i class="fa-solid fa-id-card fa-2xl text-blue-500"></i>
                        <div>
                            <p class="text-sm">Nama Lengkap :</p>
                            <p class="text-sm" id="detailNamaLengkap"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-x-2">
                        <i class="fa-solid fa-user-shield fa-2xl text-amber-500"></i>
                        <div>
                            <p class="text-sm">Role Pengguna :</p>
                            <p class="text-sm" id="detailRole"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-x-2">
                        <i class="fa-solid fa-envelope fa-2xl text-emerald-500"></i>
                        <div>
                            <p class="text-sm">Email Pengguna :</p>
                            <p class="text-sm" id="detailEmail"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition">
                    <i class="fa-solid fa-xmark mr-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>

    <div id="modalEdit" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4">
        <div class="bg-utama text-kedua p-6 rounded-xl shadow-lg w-full max-w-2xl overflow-auto max-h-[90vh]">
            <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-user-pen mr-2"></i>Edit Pengguna</h3>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PATCH')
                <div class="flex justify-center mb-4">
                    <label for="fotoEdit" class="cursor-pointer group">
                        <div class="relative w-32 h-32 rounded-full overflow-hidden border-4 border-kedua shadow-md transition-all group-hover:scale-105">
                            <img id="previewEdit" class="w-full h-full object-cover">
                            <div id="defaultViewEdit" class="w-full h-full bg-kedua/10 flex items-center justify-center">
                                <i class="fa-solid fa-camera text-3xl text-kedua/50"></i>
                            </div>
                        </div>
                        <input type="file" name="foto_pengguna" id="fotoEdit" accept="image/*" class="hidden">
                    </label>
                </div>
                <div class="mb-4 flex flex-col gap-y-4">
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3 top-3 text-kedua"></i>
                        <input type="text" id="editNamaPengguna" name="nama_pengguna" placeholder="Nama Pengguna" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-id-card absolute left-3 top-3 text-kedua"></i>
                        <input type="text" id="editNama" name="nama_lengkap" placeholder="Nama Lengkap" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama placeholder:text-kedua">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-user-tag absolute left-3 top-3 text-kedua"></i>
                        <select id="editRole" name="role_pengguna" required class="w-full pl-10 px-3 py-2 border border-kedua rounded-lg bg-utama">
                            @foreach($roles as $role)
                                <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-5 py-2 bg-gray-500 text-kedua rounded-lg hover:scale-105 transition">
                        <i class="fa-solid fa-xmark mr-2"></i>Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-yellow-500 text-kedua rounded-lg hover:scale-105 transition">
                        <i class="fa-solid fa-save mr-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    function openModal(type) {
        closeModal();
        document.getElementById('overlay').classList.remove('hidden');
        document.getElementById('modal' + type.charAt(0).toUpperCase() + type.slice(1)).classList.remove('hidden');
        
        if (type == 'tambah') {
            setupImagePreview('fotoTambah', 'previewTambah', 'defaultViewTambah');
        }
    }

    function closeModal() {
        document.getElementById('overlay').classList.add('hidden');
        document.querySelectorAll('[id^="modal"]').forEach(m => m.classList.add('hidden'));
        
        // Reset preview untuk modal tambah
        resetPreview('fotoTambah', 'previewTambah', 'defaultViewTambah');
        
        // Reset preview untuk modal edit
        resetPreview('fotoEdit', 'previewEdit', 'defaultViewEdit');
    }

    function resetPreview(inputId, previewId, defaultViewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        const defaultView = document.getElementById(defaultViewId);
        
        if (input) input.value = '';
        if (preview) {
            preview.src = '';
            preview.classList.add('hidden');
        }
        if (defaultView) {
            defaultView.classList.remove('hidden');
        }
    }

    function showDetail(pengguna) {
        document.getElementById('detailId').innerText = pengguna.id_pengguna;
        document.getElementById('detailNama').innerText = pengguna.nama_pengguna;
        document.getElementById('detailNamaLengkap').innerText = pengguna.nama_lengkap;
        document.getElementById('detailRole').innerText = ucWords(pengguna.role_pengguna);
        document.getElementById('detailEmail').innerText = pengguna.email ?? 'Tidak ada email';

        const container = document.getElementById('containerImg');
        container.innerHTML = pengguna.foto_pengguna
            ? `<img src="/storage/pengguna/${pengguna.foto_pengguna}" class="w-48 h-48 object-cover rounded-lg border border-kedua shadow-lg">`
            : `<div class="w-48 h-48 bg-kedua rounded-lg flex items-center justify-center border border-kedua">
                   <i class="fa-solid fa-user text-6xl text-gray-500"></i>
               </div>`;
        openModal('detail');
    }

    function showEdit(id, namaLengkap, namaPengguna, role, foto) {
        document.getElementById('editNama').value = namaLengkap;
        document.getElementById('editNamaPengguna').value = namaPengguna;
        document.getElementById('editRole').value = role;
        document.getElementById('editForm').action = `/management/pengguna/${id}`;
        const previewImage = document.getElementById('previewEdit');

        if (foto) {
            previewImage.src = `/storage/pengguna/${foto}`;
            previewImage.classList.remove('hidden');
        } else {
            previewImage.classList.add('hidden');
        }
        // Reset preview dulu
        resetPreview('fotoEdit', 'previewEdit', 'defaultViewEdit');
        
        // Setup image preview untuk modal edit
        setupImagePreview('fotoEdit', 'previewEdit', 'defaultViewEdit');
        
        openModal('edit');
    }

    function hapusPengguna(id, nama) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            html: `Data pengguna <strong>${nama}</strong> akan dihapus permanen. Data yang berkaitan akan mengalami masalah`,
            icon: 'warning',
            timer: 2500,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            background: '#1E3A8A',
            color: '#FFFFFF',
            confirmButtonText: '<i class="fa-solid fa-trash mr-2"></i>Hapus',
            cancelButtonText: '<i class="fa-solid fa-xmark mr-2"></i>Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/management/pengguna/${id}`;
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

    function setupImagePreview(inputId, previewId, defaultViewId = null) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        const defaultView = defaultViewId ? document.getElementById(defaultViewId) : null;

        if (!input || !preview) return;

        // Remove existing listeners to prevent duplicates
        const newInput = input.cloneNode(true);
        input.parentNode.replaceChild(newInput, input);

        newInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (defaultView) defaultView.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.classList.add('hidden');
                if (defaultView) defaultView.classList.remove('hidden');
            }
        });
    }

    function ucWords(str) {
        return (str + '').replace(/\b\w/g, char => char.toUpperCase());
    }
</script>
@endsection