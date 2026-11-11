<?php
require_once 'config/functions.php';
$_SESSION=[];session_destroy();session_start();flash('Kamu sudah keluar.');redirect('index.php');
