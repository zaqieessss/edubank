<p align="center">
  <img src="public/images/logo-blue.png" alt="Edubank Logo" width="100" />
</p>

<h1 align="center">Edubank - Solusi Bank Mini Sekolah Modern, Cepat & Tepercaya</h1>

<p align="center">
  Platform manajemen keuangan dan perbankan mini sekolah digital terintegrasi (Web & Mobile) untuk mengedukasi literasi finansial siswa SMKN 1 Bantul.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/TailwindCSS-v3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind" />
  <img src="https://img.shields.io/badge/Flutter-Mobile_App-02569B?style=for-the-badge&logo=flutter&logoColor=white" alt="Flutter" />
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
</p>

<p align="center">
  🌐 <strong>Live Production URL:</strong> <a href="https://edubank.web.id" target="_blank">https://edubank.web.id</a>
</p>

---

## 🌟 Fitur Utama

### 1. Multi-Role System (Admin, Teller, Nasabah Siswa)
* **Administrator**:
  * Manajemen pengguna, master siswa (impor file Excel/CSV, pembaruan angkatan & kelas).
  * Manajemen rekening nasabah, aktivasi/deaktivasi status akun.
  * Audit log transaksi, buku besar akuntansi, dan rekapitulasi keuangan sekolah.
* **Teller (Petugas Loket)**:
  * Pembukaan rekening tabungan baru (pembuatan nomor rekening otomatis, aktivasi akun login, cetak kartu nasabah digital dengan QR Code).
  * Layanan Setor Tunai & Tarik Tunai cepat via pencarian NIS, nomor rekening, atau scan QR Code.
  * Sistem Penutupan Kas Harian (*Daily Closing*) dengan verifikasi selisih kas fisik vs sistem.
* **Nasabah (Siswa / EduBank Mobile & Web)**:
  * Pemantauan saldo tabungan *real-time*.
  * Histori mutasi debit/kredit lengkap dengan rincian struk transaksi.
  * Otorisasi transaksi tarik tunai via Push Notification PIN secara instan.
  * Unduh kartu rekening digital siap cetak berstandar kartu bank.

---

## 🛡️ Standar Keamanan & Integritas Finansial

1. **Proteksi Anti-Bruteforce Login**:
   * Sistem otomatis membatasi percobaan login yang salah (maksimal 5 kali percobaan).
   * Akun dikunci sementara (*rate limit lockout*) selama 30 detik untuk menggagalkan serangan otomatis.
2. **Double-Entry Bookkeeping (Jurnal Akuntansi Berpasangan)**:
   * Setiap mutasi setoran dan penarikan tercatat ke dalam sistem pembukuan debit-kredit otomatis (Kas Teller vs Simpanan Nasabah).
3. **Dual-Factor Push PIN Authorization**:
   * Penarikan tunai di loket teller dapat diotorisasi langsung oleh siswa melalui konfirmasi PIN 6-digit di perangkat mobile/web mereka secara aman.
4. **Perlindungan Data Pribadi (UU PDP)**:
   * Seluruh data identitas siswa dilindungi dan tidak menyimpan kredensial mentah (menggunakan enkripsi hash Bcrypt & token Sanctum).

---

## 🚀 Panduan Instalasi Lokal (Local Development)

### Prasyarat Sistem
* PHP >= 8.2 (Disarankan PHP 8.3)
* Composer >= 2.5
* Node.js >= 18.x & NPM
* MySQL >= 8.0 / MariaDB

### Langkah-Langkah

1. **Clone Repositori**:
   ```bash
   git clone https://github.com/zaqieessss/edubank.git
   cd edubank
   ```

2. **Install Dependensi Backend**:
   ```bash
   composer install
   ```

3. **Install Dependensi Frontend**:
   ```bash
   npm install
   npm run build
   ```

4. **Konfigurasi Environment**:
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Sesuaikan konfigurasi database Anda di dalam file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=edubank
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Migrasi Database & Seeder**:
   ```bash
   php artisan migrate --seed
   ```

6. **Hubungkan Storage Link**:
   ```bash
   php artisan storage:link
   ```

7. **Jalankan Development Server**:
   ```bash
   php artisan serve
   ```
   Akses aplikasi di browser melalui: `http://localhost:8000`.

---

## 📱 Aplikasi Mobile Android

Aplikasi mobile siswa dibangun menggunakan framework Flutter dan dapat diunduh langsung:
* File APK Rilis: Tersedia di folder `public/edubank.apk` atau unduh melalui beranda web [edubank.web.id](https://edubank.web.id).

---

## 👥 Pengembang & Hak Cipta

Dikembangkan untuk mendukung digitalisasi keuangan dan pembelajaran siswa:
* **Pengembang:** Zaqi Alfiansyah
* **Institusi:** SMK Negeri 1 Bantul (Skansaba)
* **Lisensi:** Open-Source Software di bawah lisensi [MIT License](LICENSE).
