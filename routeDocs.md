# 📚 Dokumentasi Pola Route dan Middleware

## 1. Pola Route di Laravel 🚦

Pada aplikasi ini, saya menggunakan sistem routing Laravel untuk mengatur permintaan HTTP ke controller atau closure tertentu. Berikut adalah pola-pola route yang saya gunakan:
### a. Route Dasar
```php
Route::get('/path', function() {
    // kode
});
```
- `get`, `post`, `put`, `delete`, dll. menyesuaikan dengan metode HTTP yang digunakan.
- Bisa diarahkan ke closure atau controller.

### b. Route ke Controller
```php
Route::get('/dashboard', [DashboardController::class, 'index']);
```
- Mengarahkan request ke method tertentu pada controller.

### c. Route dengan Parameter
```php
Route::get('/user/{id}', [UserController::class, 'show']);
```
- `{id}` adalah parameter yang akan diteruskan ke controller.

### d. Route Group
```php
Route::group(['prefix' => 'admin'], function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
});
```
- Digunakan untuk mengelompokkan routes dengan prefix atau middleware tertentu.

### e. Named Route
```php
Route::get('/profile', [UserController::class, 'profile'])->name('profile');
```
- Memberikan nama pada route agar mudah dipanggil.

## 2. Middleware

Middleware adalah lapisan yang memproses request sebelum mencapai controller. Biasanya digunakan untuk autentikasi, otorisasi, logging, dll.

### a. Penggunaan Middleware pada Route
```php
Route::get('/admin', [AdminController::class, 'index'])->middleware('auth');
```
- Hanya user yang sudah login (terautentikasi) yang bisa mengakses route ini.

### b. Middleware pada Route Group
```php
Route::middleware(['auth', 'checkRole:dokter'])->group(function () {
    Route::get('/dokter/dashboard', [DokterController::class, 'dashboard']);
});
```
- Semua route di dalam group ini akan melewati middleware `auth` dan `checkRole`.

### c. Custom Middleware
- Middleware dapat dibuat sendiri, misal `CheckRole` untuk membatasi akses berdasarkan peran user.
- Contoh penggunaan: `->middleware('checkRole:admin')`

## 3. Ringkasan ✨
- **Route** mengatur ke mana permintaan HTTP diarahkan.
- **Middleware** memfilter atau memproses request sebelum/atau sesudah mencapai controller.
- Kombinasi route dan middleware membuat aplikasi lebih aman dan terstruktur. 🚀

---

## 4. Penjelasan Controller yang Saya Gunakan 🧑‍💻

### 1. `DashboardController` 🏠
Controller ini saya gunakan untuk menampilkan halaman dashboard utama dan tabel data. Di dalamnya terdapat method seperti `index()` untuk dashboard, dan `tables()` untuk tampilan tabel.

### 2. `DokterController` 🩺
Controller ini saya buat khusus untuk fitur-fitur yang berkaitan dengan dokter, seperti:
- Menampilkan dashboard dokter (`dashboard()`)
- Mengelola data pasien yang diperiksa
- Manajemen data obat
- Mengelola hasil pemeriksaan dan riwayat pasien
Setiap akses ke controller ini saya lindungi dengan middleware `auth` dan `role:dokter` agar hanya dokter yang bisa mengaksesnya.

### 3. `PasienController` 🙋‍♂️
Controller ini saya gunakan untuk menangani fitur pasien, seperti:
- Menampilkan dashboard pasien (`dashboard()`)
- Melihat riwayat periksa dan melakukan pendaftaran periksa
- Melihat daftar dokter yang tersedia
Controller ini juga saya proteksi dengan middleware `auth` dan `role:pasien` supaya hanya pasien yang dapat mengakses fitur-fitur tersebut.

### 4. `AuthController` 🔐
Controller ini saya pakai untuk proses otentikasi user, mulai dari login, register, logout, hingga reset password. Semua proses pengelolaan sesi user saya atur di sini.

---

*Dokumentasi ini saya buat agar mudah dipahami dan dapat dikembangkan sesuai kebutuhan proyek serta pola yang saya gunakan pada file `routes/web.php`. 😊*
