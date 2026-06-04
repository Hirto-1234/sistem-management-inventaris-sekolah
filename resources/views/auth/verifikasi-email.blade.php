@extends('layouts.auth')

@section('title', 'Verifikasi Email')

@section('content-auth')
<div class="fixed inset-0 flex items-center justify-center bg-kedua font-utama overflow-hidden">

    <div class="w-full max-w-md bg-utama text-kedua rounded-2xl shadow-2xl p-8 border border-kedua">

        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="w-20 h-20 bg-kedua rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-envelope-circle-check text-utama text-4xl"></i>
                </div>
            </div>
            <h2 class="text-3xl font-bold tracking-wide text-kedua">Verifikasi Email</h2>
            <p class="text-kedua/70 text-sm mt-2">
                Kami telah mengirim tautan verifikasi ke email kamu. Silakan cek inbox atau folder spam.
            </p>
        </div>

        <form id="formKirimUlang" action="{{ route('verification.send') }}" method="POST" class="space-y-5">
            @csrf

            <button type="submit"
                class="w-full bg-kedua text-utama py-3 rounded-lg font-semibold flex justify-center items-center gap-3 hover:scale-105 transition duration-200 shadow-md">
                <i class="fa-solid fa-paper-plane"></i>Kirim Ulang Email
            </button>

            <div class="text-center mt-4">
                <p class="text-kedua text-sm">
                    <a href="{{ route('logout') }}" class="text-kedua font-semibold hover:underline transition">
                        Keluar Akun
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
        @if(session('message'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('message') }}'
            });
        @endif

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
                title: 'Gagal',
                html: errorList,
                confirmButtonColor: '#d33'
            });
        @endif
    });

    const form = document.getElementById('formKirimUlang');
    form.addEventListener('submit', function (e) {
        const submitButton = this.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;  // Disable button
            submitButton.innerText = 'Sedang Memproses...'; // Optional: ganti teks
        }
    })
</script>
@endsection