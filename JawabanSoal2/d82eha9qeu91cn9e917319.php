<?php
include "config.php";
$email = $_GET['12j4has8'];

if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
  }

  $sql  = "UPDATE `user` SET `status`='1' WHERE `email` = '$email' ";
  $datas = $mysqli->query($sql);
  echo "<h1>Terima Kasih akun anda telah aktif</h1>";
?>

