<?php
//https://auth-db429.hstgr.io/index.php?db=u654786374_sems
/* Membuat variabel, ubah sesuai dengan nama host dan database pada hosting */
$host	= "localhost";
$user	= "u654786374_sems";
$pass	= "N3Wd3vc4mp";
$db		= "u654786374_sems";

$key = "50bfbf83-76db-4cc8-9cc9-eaeb6d5a99b4";
//Menggunakan objek mysqli untuk membuat koneksi dan menyimpanya dalam variabel $mysqli	//
$mysqli = new mysqli($host, $user, $pass, $db);


//Menentukan timezone //
date_default_timezone_set('Asia/jakarta'); 

//Membuat variabel yang menyimpan nilai waktu //
$nama_hari 	= array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
$hari		= date("w");
$hari_ini 	= $nama_hari[$hari];

$tgl_sekarang = date("d");
$bln_sekarang = date("m");
$thn_sekarang = date("Y");

$tanggal 	= date('Ymd');
$jam 		= date("H:i:s");

// echo "$tanggal"." $hari_ini ".$jam;
?>
