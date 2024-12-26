<p align="center">LaraQuickKit</p>

## About LaraQuickKit

LaraQuickKit adalah sebuah package Laravel yang dirancang untuk mempercepat pengembangan aplikasi berbasis web dengan menyediakan berbagai modul siap pakai. Package ini dirancang untuk memenuhi kebutuhan perusahaan, dengan fitur modular, autentikasi yang terintegrasi, dan kemudahan instalasi.

- Modularitas Tinggi: Pilih dan gunakan modul sesuai kebutuhan (CRM, Inventory, HR, dll).
- Autentikasi Terintegrasi: Mendukung login, registrasi, dan reset password dengan fitur roles dan permissions berbasis Spatie Permission.
- UI Kustom: Semua tampilan dibuat unik.
- Integrasi Package: Terhubung dengan package pendukung seperti Spatie Permission, DomPDF, dan lainnya.


### Persyaratan

- **PHP >= 8.1**
- **Laravel >= 10.0**
- **Composer**

### Package pendukung:
- **Framework UI**
- **Spatie/laravel-permission**
- **DomPDF**

## Instalasi

- Tahap 1: Persiapan
- **Pastikan Anda telah menginstal Laravel dengan perintah berikut:**
- laravel new project-name
- **Instal package pendukung dengan perintah berikut:**
- composer require spatie/laravel-permission
- composer require barryvdh/laravel-dompdf
- Tahap 2: Instalasi LaraQuickKit
- **Tambahkan LaraQuickKit ke dalam project Anda:**
- composer require magicbox/laraquickkit
- **Jalankan Perintah Instalasi:**
- php artisan magic:laraquickkit
- **Ikuti petunjuk di CLI untuk memilih modul yang ingin diinstal.**
- Langkah 3: Migrasi dan Seeder
- **Jalankan migrasi database:**
- php artisan migrate
- **Jalankan seeder untuk mengisi data awal (roles, permissions, dll):**
- php artisan db:seed --class=LaraQuickKitSeeder

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Konfigurasi Tambahan

Email

Untuk fitur forgot password, Anda perlu mengatur konfigurasi email di file .env. Contoh:

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=null

## License

LaraQuickKit dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
