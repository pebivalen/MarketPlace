<?php
require_once 'config/functions.php';
$id=(int)($_POST['id_produk']??0);$jumlah=max(1,(int)($_POST['jumlah']??1));
$stmt=$conn->prepare("SELECT stok FROM produk WHERE id_produk=?");$stmt->bind_param('i',$id);$stmt->execute();$p=$stmt->get_result()->fetch_assoc();
if(!$p){flash('Produk tidak ditemukan.','danger');redirect('index.php');}
if($jumlah>(int)$p['stok']){flash('Jumlah melebihi stok tersedia.','warning');redirect('detail.php?id='.$id);}
$_SESSION['cart']=$_SESSION['cart']??[];$old=(int)($_SESSION['cart'][$id]??0);
$_SESSION['cart'][$id]=min($old+$jumlah,(int)$p['stok']);
flash('Produk berhasil ditambahkan ke keranjang.');redirect('keranjang.php');
