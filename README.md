# 🏥 Sistem Manajemen Klinik

### Tentang Saya
- **Nama Lengkap**: Bintang Ilham Kurniawan
- **NIM**: A11.2022.14399
- **Kelas**: WD05

## 📋 Gambaran Umum
Aplikasi manajemen klinik berbasis web ini dikembangkan dengan teknologi modern untuk memudahkan pengelolaan data pasien, dokter, dan obat-obatan. Dibangun menggunakan Laravel 12 sebagai backend framework dengan dukungan MySQL untuk database, serta AdminLTE untuk tampilan antarmuka yang responsif dan user-friendly.

## ✨ Fitur Unggulan
- **Sistem Autentikasi**
  - Login multi-role (Admin, Dokter, Pasien)
  - Registrasi akun pasien
  - Manajemen sesi pengguna

- **Modul Pasien**
  - Pendaftaran akun pasien baru
  - Pengisian form permintaan pemeriksaan
  - Melihat riwayat pemeriksaan

- **Modul Dokter**
  - Manajemen antrian pasien
  - Pencatatan hasil pemeriksaan
  - Resep dan rekomendasi obat

- **Manajemen Obat**
  - Input data obat baru
  - Update stok obat
  - Pencarian dan filter data obat
  - Penghapusan data obat

- **Laporan & Riwayat**
  - Riwayat pemeriksaan pasien
  - Catatan medis lengkap
  - Resep dan pengobatan

## 3. Struktur Folder (Non-default Laravel)
```
app/
  Http/Controllers/
    AuthController.php        # Login, register
    PasienController.php      # CRUD pasien, form periksa, riwayat
    DokterController.php      # Antrian periksa, update, CRUD obat
  Models/
    Pasien.php                # Eloquent model pasien
    Periksa.php               # Eloquent model periksa (keluhan, catatan_dokter, status)
    Obat.php                  # Eloquent model obat

database/
  migrations/
    *_create_pasiens_table.php
    *_create_periksas_table.php
    *_create_obats_table.php
  seeders/                   # (opsional) data awal

public/
  adminlte/                  # template AdminLTE (CSS/JS)
  plugins/datatables/        # DataTables assets

resources/views/
  layouts/dokter.blade.php   # layout utama dokter
  layouts/pasien.blade.php   # layout utama pasien
  pasien/
    register.blade.php       # form registrasi
    periksa.blade.php        # form permintaan periksa
    riwayat.blade.php        # tabel riwayat pemeriksaan
  dokter/
    dashboard.blade.php      # statistik dashboard dokter
    periksa.blade.php        # tabel antrian periksa
    periksa-edit.blade.php   # modal edit pemeriksaan
    obat.blade.php           # form + tabel obat (DataTables)

routes/web.php               # route definisi untuk pasien & dokter
```

## 4. Alur Sistem
1. **Pasien Register**: POST `/register` → `AuthController@register` → tabel `pasiens`.
2. **Pasien Login**: POST `/login` → `AuthController@login`.
3. **Permintaan Pemeriksaan**: POST `/pasien/periksa` → `PasienController@storePeriksa` → `periksas` status `pending`.
4. **Dokter Antrian**: GET `/dokter/periksa` → `DokterController@periksa` → tampilkan DataTable.
5. **Update Pemeriksaan**: PUT `/dokter/periksa/{id}` → `DokterController@periksaUpdate` (simpan `catatan_dokter`, status `done`).
6. **CRUD Obat**: 
   - GET `/dokter/obat` → form + DataTable.
   - POST `/dokter/obat` → `DokterController@storeObat`.
   - PUT `/dokter/obat/{id}` → `DokterController@updateObat`.
   - DELETE `/dokter/obat/{id}` → `DokterController@destroyObat`.
7. **Riwayat Pasien**: GET `/pasien/riwayat` → `PasienController@riwayat` → DataTable.

## 5. Dependensi & Teknologi ⚙️
- **Laravel 12.x** 🚀, **PHP 8.x**, **Composer**
- **MySQL** 🐬
- **AdminLTE** 🎨
- **jQuery**, **DataTables** (responsive, buttons)
- **AJAX & Modal**: CRUD obat tanpa reload penuh

## 6. Cara Penggunaan 🛠️
1. `composer install`
2. `php artisan migrate`
3. `php artisan serve`
4. Akses di `http://localhost:8000` 🚦

## 7. Penjelasan Arsitektur MVC 🗂️

Aplikasi ini saya bangun dengan pola **MVC (Model-View-Controller)**:
- **Model** 🗃️: Berisi logika dan struktur data, misal model `Pasien`, `Periksa`, dan `Obat` yang berhubungan langsung dengan database MySQL.
- **View** 👁️: Semua tampilan antarmuka (UI) saya buat dengan Blade, AdminLTE, dan DataTables agar user experience lebih nyaman dan modern.
- **Controller** 🧑‍💻: Di sinilah saya mengatur alur data dari model ke view, serta menangani request dari user (misal login, CRUD, dll).

Dengan pola MVC ini, kode jadi lebih terstruktur, mudah dipelihara, dan scalable untuk kebutuhan pengembangan ke depan.

## 8. User Stories / Demonstrasi

### 7.1 Pasien
- **Register Pasien**: Demonstrasikan pasien melakukan registrasi di `/register`.
- **Login Pasien**: Demonstrasikan pasien login menggunakan akun terdaftar.
- **Fitur Periksa**: Demonstrasikan pasien mengisi form periksa (`/pasien/periksa`), memasukkan keluhan, dan submit.

### 7.2 Dokter
- **Login Dokter**: Demonstrasikan dokter login di `/login` dengan role dokter.
- **Fitur Memeriksa**: Demonstrasikan dokter melihat daftar periksa pending, membuka detail periksa (`/dokter/periksa/{id}/edit`), mengisi `catatan_dokter`, dan submit.
- **Fitur CRUD Obat**: Demonstrasikan doktor menambah, mengedit, menghapus, dan melihat data obat di `/dokter/obat`.

### 7.3 Tampilan AdminLTE
- **Kesesuaian UI**: Demonstrasikan semua halaman menggunakan template AdminLTE (navigation, card, form, tabel) untuk tampilan konsisten.

---