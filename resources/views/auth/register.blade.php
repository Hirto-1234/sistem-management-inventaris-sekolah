@extends('layouts.auth')

@section('title', 'Register')

@section('content-auth')
<div class="fixed inset-0 flex items-center justify-center bg-kedua font-utama overflow-hidden">

    <div class="w-full max-w-2xl bg-utama text-kedua rounded-2xl shadow-2xl p-8 border border-kedua">

        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold tracking-wide text-kedua"><i class="fa-solid fa-user-plus"></i> Register</h2>
            <p class="text-kedua/70 text-sm mt-2">Buat akun untuk mengakses sistem inventaris</p>
        </div>

        <form id="formRegister" action="{{ route('auth.register.post') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div class="flex justify-center mb-4">
            <label for="foto_pengguna" class="cursor-pointer group">
                <div class="relative w-24 h-24 rounded-full overflow-hidden border-4 border-kedua shadow-md transition-all group-hover:scale-105">

                    <img id="foto-preview" src="" class="hidden w-full h-full object-cover">
                    <div id="default-view" class="w-full h-full bg-kedua/10 flex items-center justify-center">
                        <i class="fa-solid fa-camera text-3xl text-kedua/50"></i>
                    </div>

                </div>
                <input type="file" name="foto_pengguna" id="foto_pengguna" accept="image/*" class="hidden">
            </label>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

            <div class="relative">
                <i class="fa-solid fa-user absolute left-3 top-3.5 text-kedua"></i>
                <input type="text" name="nama_lengkap" placeholder="Nama lengkap" required class="w-full bg-utama text-kedua border border-kedua rounded-lg pl-10 pr-3 py-2.5 placeholder:text-kedua/50 transition" value="{{ old('nama_lengkap') }}">
            </div>

            <div class="relative">
                <i class="fa-solid fa-at absolute left-3 top-3.5 text-kedua"></i>
                <input type="text" name="nama_pengguna" placeholder="Username" required class="w-full bg-utama text-kedua border border-kedua rounded-lg pl-10 pr-3 py-2.5 placeholder:text-kedua/50 transition" value="{{ old('nama_pengguna') }}">
            </div>

            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-3 top-3.5 text-kedua"></i>
                <input type="email" name="email" placeholder="Email" required class="w-full bg-utama text-kedua border border-kedua rounded-lg pl-10 pr-3 py-2.5 placeholder:text-kedua/50 transition" value="{{ old('email') }}">
            </div>

            <div class="relative">
                <i class="fa-solid fa-lock absolute left-3 top-3.5 text-kedua"></i>
                <input type="password" name="password_pengguna" placeholder="Password" required autocomplete="new-password" class="w-full bg-utama text-kedua border border-kedua rounded-lg pl-10 pr-3 py-2.5 placeholder:text-kedua/50 transition">
            </div>
        </div>

        <button type="submit" class="w-full bg-kedua text-utama py-3 rounded-lg font-semibold flex justify-center items-center gap-3 hover:scale-105 transition duration-200 shadow-md">
            <i class="fa-solid fa-user-plus"></i>Register
        </button>

        <div class="text-center mt-4">
            <p class="text-kedua text-sm">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-kedua font-semibold hover:underline transition">
                    Login di sini
                </a>
            </p>
        </div>
        </form>
    </div>
</div>

{{-- SWEETALERT2 TOAST --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        @if(session('error_store'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error_store') }}'
            });
        @endif

        @if($errors->any())
            let errorList = '<ul class="text-left list-disc pl-5">';
            @foreach($errors->all() as $error)
                errorList += '<li>{{ $error }}</li>';
            @endforeach
            errorList += '</ul>';

            Swal.fire({
                icon: 'error',
                title: 'Registrasi Gagal',
                html: errorList,
                confirmButtonColor: '#d33'
            });
        @endif
    });

    // Preview foto upload
    const input = document.getElementById('foto_pengguna');
    const preview = document.getElementById('foto-preview');
    const defaultView = document.getElementById('default-view');

    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                defaultView.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            defaultView.classList.remove('hidden');
        }
    });

    const form = document.getElementById('formRegister');
    form.addEventListener('submit', function (e) {
        const submitButton = this.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;  // Disable button
            submitButton.innerText = 'Sedang Memproses...'; // Optional: ganti teks
        }
    })
</script>
@endsection