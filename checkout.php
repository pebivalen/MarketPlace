<?php
require_once 'config/functions.php';require_login();
$cart=$_SESSION['cart']??[];if(!$cart){flash('Keranjang kosong.','warning');redirect('keranjang.php');}
$ids=array_map('intval',array_keys($cart));$res=$conn->query("SELECT * FROM produk WHERE id_produk IN (".implode(',',$ids).")");$items=[];$total=0;while($p=$res->fetch_assoc()){$qty=(int)$cart[$p['id_produk']];if($qty<1||$qty>$p['stok'])continue;$p['jumlah']=$qty;$p['subtotal']=$qty*(float)$p['harga'];$total+=$p['subtotal'];$items[]=$p;}
if(!$items){flash('Produk tidak tersedia atau stok habis.','warning');redirect('keranjang.php');}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $alamat=trim($_POST['alamat_pengiriman']??'');
 if($alamat==='')$error='Alamat pengiriman wajib diisi.';
 else{
  $conn->begin_transaction();
  try{
   // Kunci dan periksa stok ulang untuk mencegah pesanan melebihi stok.
   foreach($items as $it){$s=$conn->prepare("SELECT stok FROM produk WHERE id_produk=? FOR UPDATE");$s->bind_param('i',$it['id_produk']);$s->execute();$fresh=$s->get_result()->fetch_assoc();if(!$fresh||(int)$fresh['stok']<$it['jumlah'])throw new Exception('Stok '.$it['nama_produk'].' tidak mencukupi.');}
   $uid=(int)current_user()['id_user'];$st=$conn->prepare("INSERT INTO pesanan(id_user,total_harga,alamat_pengiriman,status) VALUES(?,?,?,'Menunggu')");$st->bind_param('ids',$uid,$total,$alamat);$st->execute();$order=$conn->insert_id;
   $di=$conn->prepare("INSERT INTO detail_pesanan(id_pesanan,id_produk,jumlah,harga,subtotal) VALUES(?,?,?,?,?)");
   $up=$conn->prepare("UPDATE produk SET stok=stok-? WHERE id_produk=?");
   foreach($items as $it){$pid=(int)$it['id_produk'];$qty=(int)$it['jumlah'];$price=(float)$it['harga'];$sub=(float)$it['subtotal'];$di->bind_param('iiidd',$order,$pid,$qty,$price,$sub);$di->execute();$up->bind_param('ii',$qty,$pid);$up->execute();}
   $conn->commit();unset($_SESSION['cart']);flash('Pesanan berhasil dibuat. Nomor pesanan: '.$order);redirect('pesanan.php');
  }catch(Throwable $ex){$conn->rollback();$error=$ex->getMessage();}
 }
}
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Checkout - MarketKu</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet"></head><body><nav class="navbar navbar-dark navbar-market"><div class="container"><a class="navbar-brand" href="index.php">MarketKu</a></div></nav><main class="container py-4"><h2 class="fw-bold">Checkout</h2><?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?><div class="row g-4"><div class="col-md-7"><form method="post" class="card content-card p-4"><label class="form-label">Nama penerima</label><input class="form-control mb-3" value="<?=e(current_user()['nama'])?>" disabled><label class="form-label">Alamat pengiriman</label><textarea class="form-control mb-3" name="alamat_pengiriman" rows="4" required><?=e($_POST['alamat_pengiriman']??'')?></textarea><button class="btn btn-primary">Buat Pesanan</button></form></div><div class="col-md-5"><div class="card content-card p-4"><h5>Ringkasan Pesanan</h5><?php foreach($items as $it):?><div class="d-flex justify-content-between py-2"><span><?=e($it['nama_produk'])?> × <?=$it['jumlah']?></span><b><?=rupiah($it['subtotal'])?></b></div><?php endforeach;?><hr><h5 class="price">Total <?=rupiah($total)?></h5></div></div></div></main></body></html>
