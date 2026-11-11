<?php
require_once 'config/functions.php';
if(current_user()) redirect('index.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $nama=trim($_POST['nama']??'');$email=strtolower(trim($_POST['email']??''));$pass=$_POST['password']??'';
 if($nama===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($pass)<8){$error='Isi nama dan email dengan benar. Password minimal 8 karakter.';}
 else{$hash=password_hash($pass,PASSWORD_DEFAULT);$stmt=$conn->prepare("INSERT INTO users(nama,email,password,role) VALUES(?,?,?,'pembeli')");$stmt->bind_param('sss',$nama,$email,$hash);if($stmt->execute()){flash('Pendaftaran berhasil. Silakan login.');redirect('login.php');}else{$error='Email sudah digunakan atau pendaftaran gagal.';}}
}
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Daftar - MarketKu</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet"></head><body><div class="container py-5" style="max-width:520px"><div class="card content-card p-4"><h3 class="fw-bold">Daftar MarketKu</h3><?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?><form method="post"><label class="form-label">Nama</label><input class="form-control mb-3" name="nama" required><label class="form-label">Email</label><input class="form-control mb-3" type="email" name="email" required><label class="form-label">Password (minimal 8 karakter)</label><input class="form-control mb-3" type="password" name="password" minlength="8" required><button class="btn btn-primary w-100">Daftar</button></form><p class="mt-3 mb-0">Sudah punya akun? <a href="login.php">Login</a></p><a class="mt-2" href="index.php">Kembali ke beranda</a></div></div></body></html>
