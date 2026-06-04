@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content-auth')
<div class="fixed inset-0 flex items-center justify-center bg-kedua font-utama overflow-hidden">

    <div class="w-full max-w-md bg-utama text-kedua rounded-2xl shadow-2xl p-8 border border-kedua">

        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="w-20 h-20 bg-kedua rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-right-to-bracket text-utama text-4xl"></i>
                </div>
            </div>
            <h2 class="text-3xl font-bold tracking-wide text-kedua">Reset Password</h2>
            <p class="text-kedua/70 text-sm mt-2">Masukkan Password baru</p>
        </div>

        <form id="formUpdatePW" action="{{ route('reset-password') }}" method="POST" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            <div class="relative">
                <i class="fa-solid fa-lock absolute left-3 top-3.5 text-kedua"></i>
                <input type="password" name="password" placeholder="Masukkan password" required
                    class="w-full bg-utama text-kedua border border-kedua rounded-lg pl-10 pr-3 py-2.5 focus:ring-2 focus:ring-kedua focus:outline-none placeholder:text-kedua/50 transition">
            </div>

            <div class="relative">
                <i class="fa-solid fa-lock absolute left-3 top-3.5 text-kedua"></i>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password" required
                    class="w-full bg-utama text-kedua border border-kedua rounded-lg pl-10 pr-3 py-2.5 focus:ring-2 focus:ring-kedua focus:outline-none placeholder:text-kedua/50 transition">
            </div>

            <button type="submit"
                class="w-full bg-kedua text-utama py-3 rounded-lg font-semibold flex justify-center items-center gap-3 hover:scale-105 transition duration-200 shadow-md">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>Reset Password
            </button>
        </form>
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

        @if($errors->any())
            let errorList = '<ul class="text-left list-disc pl-5">';
            @foreach($errors->all() as $error)
                errorList += '<li>{{ $error }}</li>';
            @endforeach
            errorList += '</ul>';

            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                html: errorList,
                confirmButtonColor: '#d33'
            });
        @endif
    });
    const form = document.getElementById('formUpdatePW');
    form.addEventListener('submit', function (e) {
        const submitButton = this.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;  // Disable button
            submitButton.innerText = 'Sedang Memproses...'; // Optional: ganti teks
        }
    })
</script>
@endsection
