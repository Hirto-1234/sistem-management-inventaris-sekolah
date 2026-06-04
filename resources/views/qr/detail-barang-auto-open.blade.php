<script>
    // Data dari backend
    let dataQR = @json($data);

    // Kirim data ini ke localStorage untuk diproses oleh halaman utama
    localStorage.setItem('qrData', JSON.stringify(dataQR));

    // Redirect kembali ke halaman dashboard barang
    window.location.href = "/management/inventaris-barang";
</script>