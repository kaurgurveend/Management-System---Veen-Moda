# Sistem Stok Toko Tekstil - VeenModa

Aplikasi web untuk mengelola stok kain tekstil dengan fitur dashboard, input stok, manajemen staff, dan activity logging.

## 🚀 Fitur Utama

### Untuk Semua User (Admin & Staff)
- 📊 **Dashboard** - Statistik stok real-time
- 📦 **Daftar Stok** - Lihat semua produk kain
- ➕ **Input Barang** - Tambah produk kain baru dengan multiple warna dan stok
- ✏️ **Edit Stok** - Ubah data produk dan stok

### Khusus Admin
- 👥 **Manajemen Staff** - Kelola akun staff (tambah, edit, hapus, aktifkan/nonaktifkan)
- 🏷️ **Kategori Kain** - Kelola jenis-jenis kain
- 📝 **Activity Log** - Lihat semua aktivitas user dengan filter

## 📋 Persyaratan Sistem

- PHP 8.1+
- MySQL 5.7+
- Composer
- Node.js (untuk asset compilation)

## 🔧 Instalasi

### 1. Clone Repository
```bash
cd /Users/gurveenderjeetkaur/Documents/toko-tekstil
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=veenmoda
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Jalankan Migration & Seeding
```bash
php artisan migrate:refresh --seed
```

### 6. Jalankan Server
```bash
php artisan serve
```

Akses aplikasi di: `http://127.0.0.1:8000`

## 👤 Akun Login Default

### Admin
- **Email:** admin@example.com
- **Password:** password

### Staff
- **Email:** staff@example.com
- **Password:** password

Lihat file `KREDENSIAL_LOGIN.md` untuk informasi lengkap.

## 📱 Panduan Penggunaan

### 1. Dashboard
- Menampilkan statistik total produk, stok, kategori, dan stok rendah
- Untuk admin: menampilkan statistik staff
- Menampilkan produk dengan stok rendah (< 10 pcs)
- Menampilkan statistik stok per kategori
- Menampilkan aktivitas terbaru

### 2. Input Barang
1. Klik menu "➕ Input Barang"
2. Pilih kategori kain
3. Masukkan nama kain
4. Masukkan harga per pcs
5. Tambahkan warna dan stok:
   - Masukkan nama warna (contoh: Navy, Merah, Biru)
   - Masukkan jumlah stok
   - Klik "+ Tambah Warna Lain" untuk menambah warna lainnya
6. Klik "Simpan Data Kain"

### 3. Daftar Stok
1. Klik menu "📦 Daftar Stok"
2. Lihat semua produk kain dengan detail warna dan stok
3. Gunakan fitur pencarian untuk mencari kain
4. Klik "Edit" untuk mengubah data
5. Klik "Hapus" (admin only) untuk menghapus

### 4. Manajemen Staff (Admin Only)
1. Klik menu "👥 Manajemen Staff"
2. Lihat daftar semua staff
3. Klik "+ Tambah Staff Baru" untuk membuat akun baru
4. Klik "Edit" untuk mengubah data staff
5. Klik "Aktifkan/Nonaktifkan" untuk mengubah status
6. Klik "Hapus" untuk menghapus akun

### 5. Kategori Kain (Admin Only)
1. Klik menu "🏷️ Kategori Kain"
2. Lihat daftar kategori
3. Klik "+ Tambah Kategori" untuk membuat kategori baru
4. Klik "Edit" untuk mengubah kategori
5. Klik "Hapus" untuk menghapus kategori (jika tidak digunakan)

### 6. Activity Log (Admin Only)
1. Klik menu "📝 Activity Log"
2. Lihat semua aktivitas user
3. Filter berdasarkan user atau tanggal
4. Aktivitas mencakup: tambah produk, edit produk, hapus produk, dll

## 🗄️ Struktur Database

### Tabel: products
```
id (Primary Key)
category_id (Foreign Key → categories)
name (String)
price (Decimal)
created_at, updated_at
```

### Tabel: product_variants
```
id (Primary Key)
product_id (Foreign Key → products)
color (String)
stock (Integer)
created_at, updated_at
```

### Tabel: categories
```
id (Primary Key)
name (String)
description (Text, nullable)
created_at, updated_at
```

### Tabel: activity_logs
```
id (Primary Key)
user_id (Foreign Key → users)
activity (Text)
created_at, updated_at
```

### Tabel: users
```
id (Primary Key)
name (String)
email (String)
password (String)
role (String: admin/staff)
is_active (Boolean)
created_at, updated_at
```

## 🔐 Keamanan

- ✅ Authentication dengan Laravel Auth
- ✅ Authorization dengan Gates (admin-only)
- ✅ Middleware untuk cek user aktif
- ✅ CSRF Protection
- ✅ Password hashing dengan bcrypt
- ✅ Activity logging untuk audit trail

## 🐛 Troubleshooting

### Error: "Column not found: 1054 Unknown column 'stock'"
**Solusi:** Jalankan `php artisan migrate:refresh --seed`

### Error: "SQLSTATE[HY000]: General error: 1030 Got error"
**Solusi:** Pastikan MySQL service berjalan dan database sudah dibuat

### Error: "Class not found"
**Solusi:** Jalankan `composer dump-autoload`

### Error: "No application encryption key has been set"
**Solusi:** Jalankan `php artisan key:generate`

## 📝 Catatan Penting

1. **Backup Database** - Selalu backup database sebelum melakukan operasi penting
2. **User Aktif** - Staff hanya bisa login jika status `is_active` = true
3. **Kategori** - Kategori tidak bisa dihapus jika masih digunakan oleh produk
4. **Stok Rendah** - Produk dengan stok < 10 pcs akan ditampilkan di dashboard dengan warna merah
5. **Activity Log** - Semua aktivitas user dicatat otomatis untuk audit trail

## 📞 Support

Untuk bantuan atau pertanyaan, silakan hubungi tim development.

## 📄 Lisensi

Proprietary - Hak Cipta VeenModa
