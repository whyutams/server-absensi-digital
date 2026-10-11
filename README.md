# Server Absensi Digital

Backend Server Absensi Digital untuk Aplikasi Sistem Monitoring Siswa (SISMON) sebagai REST API yang dibuat menggunakan Framework Laravel 11

## Teknologi
- **Framework**: Laravel 11
- **Bahasa**: PHP >= 8.2
- **Database**: MySQL / MariaDB

---

## Panduan Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- MySQL / MariaDB (Laragon / XAMPP)

### Langkah Instalasi
1. **Clone Repository**
   ```bash
   git clone https://github.com/whyutams/server-absensi-digital.git
   cd server-absensi-digital
   ```

2. **Install Dependensi Composer**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   *Pada Windows (PowerShell / Command Prompt):*
   ```powershell
   copy .env.example .env
   ```
   Buka file `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=server_absensi_digital
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi dan Seeder Database**
   Pastikan database telah dibuat di MySQL, lalu jalankan:
   ```bash
   php artisan migrate --seed
   ```

6. **Buat Storage Link**
   ```bash
   php artisan storage:link
   ```

7. **Cek IP Lokal**
   dengan cara menjalankan perintah berikut:
   ```bash
   ipconfig
   ```
   kemudian copy IPv4 anda yang muncul.

8. **Jalankan Server**
   ```bash
   php artisan serve --host <IPv4>
   ```
   Server backend akan berjalan di `http://IPv4:8000`.

   PENTING: jika sudah membaca dokumentasi aplikasi SISMON, pastikan menggunakan IPv4 yang sama dengan server, dan konfigurasi di ApiClient. kemudian, jika menggunakan emulator external seperti handphone maka wajib terhubung ke jaringan local yang sama.