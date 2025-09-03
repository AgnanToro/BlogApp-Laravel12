# Cara Menjalankan BlogSpace (Laravel Blog Test Case)

Ikuti langkah berikut untuk menjalankan aplikasi ini di lokal:

---

## 1. Clone Repository
```bash
git clone <repository-url>
cd Dafidea - testcase
```

## 2. Install Dependency PHP
```bash
composer install
```

## 3. Install Dependency Frontend (Vite/JS/CSS)
```bash
npm install
npm run build
```

## 4. Copy & Edit .env
```bash
cp .env.example .env
# Edit konfigurasi database di .env jika perlu
php artisan key:generate
```

## 5. Migrasi & Seed Database
```bash
php artisan migrate
php artisan db:seed

gunakan file database  db_blog.sql
```

## 6. Jalankan Aplikasi
```bash
php artisan serve
```
Akses aplikasi di: http://127.0.0.1:8000

---

### Akun Admin Default
- Email: agnanpes1@gmail.com
- Password: admin123

---

## Cara Lupa Password Admin
1. Buka halaman login admin: `/admin/login`
2. Klik link "Lupa Password?"
3. Masukkan email admin, klik submit
4. Cek  link reset password di storage/app/logs/laravel.log(pastikan konfigurasi mail di .env sudah benar)
5. klik link reset di laravel.log , ubah password sesuai yg anda inginkan
6. Jika gagal kirim email, cek error detail di file log Laravel: `storage/logs/laravel.log`

---

**Catatan:**
- Bisa pakai MySQL atau SQLite (edit .env sesuai kebutuhan)
- Semua migration & seeder sudah siap
- Untuk development, pastikan sudah install Node.js & NPM
- Jika ada error, cek detail error di `storage/logs/laravel.log`

---

Selesai! Aplikasi siap digunakan 🚀
