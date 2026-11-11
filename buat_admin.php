<?php
// Jalankan sekali setelah upload, lalu SEGERA HAPUS file ini dari htdocs.
require_once 'config/functions.php';
$email='admin@marketku.com';
$cek=$conn->prepare("SELECT id_user FROM users WHERE email=?");$cek->bind_param('s',$email);$cek->execute();
if($cek->get_result()->num_rows){exit('Akun admin sudah ada. Hapus file buat_admin.php sekarang.');}
$nama='Administrator MarketKu';$hash=password_hash('MarketKu#2026',PASSWORD_DEFAULT);
$s=$conn->prepare("INSERT INTO users(nama,email,password,role) VALUES(?,?,?,'admin')");$s->bind_param('sss',$nama,$email,$hash);
if($s->execute())echo 'Admin berhasil dibuat. Email: admin@marketku.com | Password: MarketKu#2026. SEGERA HAPUS file buat_admin.php dari htdocs dan ubah password admin setelah login.';
else echo 'Gagal membuat admin.';
