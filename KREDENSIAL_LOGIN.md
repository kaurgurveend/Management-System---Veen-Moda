# Kredensial Login - Toko Tekstil

## Akun Admin
- **Email:** admin@tokotekstil.com
- **Password:** admin123
- **Role:** Admin
- **Akses:** Semua fitur termasuk hapus data, kelola user, dll

## Akun Staff
- **Email:** staff@tokotekstil.com
- **Password:** staff123
- **Role:** Staff
- **Akses:** Input data kain, monitoring stok, update stok

---

## Cara Login

1. Jalankan aplikasi dengan `php artisan serve`
2. Buka browser ke `http://localhost:8000`
3. Klik tombol **Login** di navbar
4. Masukkan email dan password sesuai role yang diinginkan
5. Setelah login, Anda akan diarahkan ke **Dashboard**

---

## Fitur Berdasarkan Role

### Admin
✅ Lihat daftar stok kain  
✅ Tambah kain baru  
✅ Edit data kain  
✅ Hapus kain  
✅ Kelola kategori  
✅ Kelola user  

### Staff
✅ Lihat daftar stok kain  
✅ Tambah kain baru  
✅ Edit data kain  
❌ Hapus kain (hanya admin)  
❌ Kelola kategori (hanya admin)  
❌ Kelola user (hanya admin)  

---

## Cara Menambah Kain Baru

1. Login sebagai Admin atau Staff
2. Klik menu **Stok Kain** atau **Tambah Kain**
3. Isi form:
   - Pilih Kategori
   - Nama Kain
   - Harga per Meter
   - Warna dan Stok (bisa tambah beberapa warna)
4. Klik tombol **Simpan**
5. Data akan tersimpan dan muncul di daftar stok

---

## Troubleshooting

### Data tidak tersimpan?
- Pastikan sudah login
- Cek semua field sudah diisi
- Pastikan database sudah di-migrate: `php artisan migrate:fresh --seed`

### Tidak bisa login?
- Pastikan database sudah di-seed: `php artisan db:seed --class=UserSeeder`
- Cek file `.env` untuk koneksi database

### Error "Gate admin-only not defined"?
- Pastikan [`AppServiceProvider.php`](app/Providers/AppServiceProvider.php:26) sudah mendefinisikan Gate
- Restart server: `php artisan serve`
