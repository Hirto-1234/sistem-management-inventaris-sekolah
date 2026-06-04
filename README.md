# One Inven

One Inven adalah aplikasi manajemen inventaris sekolah berbasis web yang dikembangkan menggunakan Laravel. Sistem ini dibuat untuk mengatasi proses pencatatan inventaris yang masih dilakukan secara manual, sehingga pengelolaan aset, peminjaman, dan pengembalian barang dapat dilakukan dengan lebih cepat, akurat, dan terorganisir.

# Techstack

- Laravel
- Tailwind CSS
- JavaScript

# Features

- Autentikasi & Authorization
- Manajemen Data Inventaris
- Peminjaman Barang
- Pengembalian Barang
- Laporan Inventaris

## Installation

### Clone Repository

```bash
git clone https://github.com/HandikaSembiring/inventaris-sekolah.git
cd one-inven
```

### Install Dependencies

```bash
composer install
npm install
```

### Configure Environment

Salin file `.env.example` menjadi `.env`.

```bash
cp .env.example .env
```

Lalu sesuaikan konfigurasi database pada file `.env`.

### Generate Application Key

```bash
php artisan key:generate
```

### Run Database Migration

```bash
php artisan migrate
```

Jalankan seeder untuk data dummy

```bash
php artisan migrate --seed
```

### Build Frontend Assets

```bash
npm run build
```

Atau untuk development:

```bash
npm run dev
```

### Run Application

```bash
php artisan serve
```

Aplikasi akan berjalan di:

```text
http://127.0.0.1:8000
```