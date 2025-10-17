# Sistem Informasi Pariwisata Waduk Manduk

Backend Laravel untuk pengelolaan konten wisata Waduk Manduk lengkap dengan API publik, layanan status operasional, serta dashboard admin berbasis Filament.

## Persiapan

1. Salin berkas lingkungan:
   ```bash
   cp .env.example .env
   ```
2. Instal dependensi PHP dan JavaScript:
   ```bash
   composer install
   npm install
   ```
3. Buat kunci aplikasi dan tautan storage publik:
   ```bash
   php artisan key:generate
   php artisan storage:link
   ```

## Dependensi Frontend

Jalankan perintah berikut setelah inisialisasi proyek untuk memasang paket frontend tambahan:

```bash
npm i @tanstack/react-query axios dayjs zod react-hot-toast leaflet react-leaflet lucide-react
npm i -D @types/leaflet
```

## Migrasi & Seeder

Jalankan migrasi berikut untuk menginisialisasi skema dan data awal:

```bash
php artisan migrate:fresh --seed
```

Seeder akan menyiapkan master data, konten awal, serta akun super admin `admin@wadukmanduk.local` dengan sandi `password123`.

## Menjalankan Aplikasi

* Server pengembangan Laravel:
  ```bash
  php artisan serve
  ```
* Dashboard admin Filament tersedia di `/admin`.
* API publik tersedia di prefix `/api/v1` dengan cache respons 5 menit.

## Pengujian

Jalankan test fitur dan unit menggunakan Pest:

```bash
php artisan test
```

## Akun Admin

Gunakan kredensial berikut untuk mengakses dashboard:

- Email: `admin@wadukmanduk.local`
- Password: `password123`

Setelah login, ganti password melalui menu profil untuk keamanan.
