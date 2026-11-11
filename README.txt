MARKETKU - PETUNJUK INSTALASI INFINITYFREE

1. Buat database MySQL melalui Control Panel InfinityFree.
2. Pilih database tersebut di phpMyAdmin, buka tab SQL, lalu jalankan isi database.sql.
   Jika tabel sudah dibuat sebelumnya, JANGAN jalankan ulang database.sql.
3. Buka config/koneksi.php dan isi password database yang benar.
   Host, username, dan nama database pada file sudah mengikuti informasi yang terlihat
   pada screenshot: sql209.infinityfree.com / if0_43404719 / if0_43404719_marketplace.
   Cocokkan kembali di menu MySQL Databases bila berbeda.
4. Upload SEMUA isi folder MarketKu_InfinityFree ke folder htdocs.
   Pastikan index.php langsung berada di htdocs, bukan di subfolder tambahan.
5. Pastikan folder uploads ada dan dapat ditulisi oleh PHP.
6. Buka https://DOMAIN-KAMU/buat_admin.php satu kali.
   Admin awal: admin@marketku.com
   Password awal: MarketKu#2026
7. SEGERA hapus buat_admin.php melalui File Manager setelah admin dibuat.
8. Login admin melalui https://DOMAIN-KAMU/login.php, lalu buka menu Admin.
9. Pembeli mendaftar melalui register.php. Admin dapat menambah, mengedit, menghapus produk,
   serta memperbarui status pesanan.
10. Setelah selesai, ubah password admin dan jangan unggah config/koneksi.php ke GitHub publik.

FITUR:
- Daftar dan detail produk
- Pencarian dan filter kategori
- Registrasi dan login
- Keranjang berbasis session
- Checkout dan penyimpanan pesanan
- Pengurangan stok saat transaksi
- Riwayat dan detail pesanan pembeli
- CRUD produk admin dan upload gambar
- Pengelolaan status pesanan

CATATAN:
- Aplikasi menggunakan PHP 8+, MySQLi, Bootstrap 5.
- Untuk produksi, gunakan HTTPS.
- Jangan bagikan kredensial database.
