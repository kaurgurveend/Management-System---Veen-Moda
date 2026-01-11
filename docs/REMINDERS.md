# Pengingat Pembayaran (Email + WhatsApp Web)

Ringkasan implementasi:

- Sistem mengirim **email otomatis** mingguan ke semua user dengan `role = admin` jika ada supplier shipment berstatus `hutang` yang jatuh tempo dalam 6 minggu ke depan dan belum dikirimi pengingat dalam 7 hari terakhir.
- Untuk **WhatsApp**, kami tidak menggunakan API eksternal. Sebagai gantinya, ada tombol **"Kirim WhatsApp"** dan **"Kirim & Tandai"** di halaman *Pengingat Pembayaran* yang membuka **WhatsApp Web** (`wa.me`) dengan pesan pra-terisi. Admin dapat mengirim manual.
- Saat email otomatis dikirim, system juga menandai `last_reminder_sent_at`. Admin dapat menandai pengingat secara manual lewat UI jika mereka mengirim WhatsApp.

Langkah konfigurasi:

1. Tambahkan nomor admin (format internasional tanpa plus, mis. `628123456789`) di `.env`:

```
WHATSAPP_ADMIN_NUMBER=628123456789
```

2. Jalankan migrasi untuk menambahkan kolom `last_reminder_sent_at`:

```
php artisan migrate
```

3. Pastikan scheduler berjalan di server (cron):

```
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

4. Pastikan konfigurasi email (MAIL_*) sudah benar sehingga Laravel dapat mengirim email.

Catatan:

- Jika kamu lebih suka menyimpan nomor admin di DB (kolom `phone` pada tabel `users`), kita bisa tambahkan migrasi untuk itu dan gunakan `User::where('role','admin')->first()->phone` sebagai sumber nomor.
- Desain ini mematuhi permintaan "jangan pakai API" dan mengandalkan WhatsApp Web untuk pengiriman pesan manual.
