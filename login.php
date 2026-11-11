<?php
require_once 'config/functions.php';
if(current_user()) redirect('index.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $email=strtolower(trim($_POST['email']??''));$pass=$_POST['password']??'';
 $stmt=$conn->prepare("SELECT id_user,nama,email,password,role FROM users WHERE email=? LIMIT 1");$stmt->bind_param('s',$email);$stmt->execute();$u=$stmt->get_result()->fetch_assoc();
 if($u && password_verify($pass,$u['password'])){session_regenerate_id(true);$_SESSION['user']=['id_user'=>$u['id_user'],'nama'=>$u['nama'],'email'=>$u['email'],'role'=>$u['role']];flash('Login berhasil.');redirect($u['role']==='admin'?'admin/index.php':'index.php');}
 $error='Email atau password salah.';
}
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login - MarketKu</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet"></head><body><div class="container py-5" style="max-width:520px"><div class="card content-card p-4"><h3 class="fw-bold">Login MarketKu</h3><?php show_flash();if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?><form method="post"><label class="form-label">Email</label><input class="form-control mb-3" type="email" name="email" required><label class="form-label">Password</label><input class="form-control mb-3" type="password" name="password" required><button class="btn btn-primary w-100">Login</button></form><p class="mt-3 mb-0">Belum punya akun? <a href="register.php">Daftar</a></p><a class="mt-2" href="index.php">Kembali ke beranda</a></div></div></body></html>
