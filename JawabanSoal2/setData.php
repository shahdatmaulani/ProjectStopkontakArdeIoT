<?php

include "config.php";

$idHardware = $_GET['idHardware'];
$stack = $_GET['stack'];
$l1 = $_GET['l1'];
$l2 = $_GET['l2'];
$l3 = $_GET['l3'];
$volt = $_GET['volt'];
$power = $_GET['power'];
$tanggal = "$thn_sekarang-$bln_sekarang-$tgl_sekarang";
$jam = "$jam";

$powerL1 = $l1 * $volt;
$powerL2 = $l2 * $volt;
$powerL3 = $l3 * $volt;
// Periksa apakah koneksi sudah berhasil
if ($mysqli->connect_error) {
  die("Koneksi gagal: " . $mysqli->connect_error);
}


if ($idHardware == "smp000088") {
 

  $sql  = "INSERT INTO `sems`(`id`, `amp`, `volt`, `power`, `date`, `time`) 
        VALUES ('$idHardware','$l1','$volt','$powerL1','$tanggal','$jam')";
  $datas = $mysqli->query($sql);
}


if ($idHardware == "smp000089") {
 
  $sql  = "INSERT INTO `sems`(`id`, `amp`, `volt`, `power`, `date`, `time`) 
  VALUES ('smp000088','$l1','$volt','$powerL1','$tanggal','$jam')";
  $datas = $mysqli->query($sql);

  $sql1  = "INSERT INTO `sems`(`id`, `amp`, `volt`, `power`, `date`, `time`) 
  VALUES ('$idHardware','$l2','$volt','$powerL2','$tanggal','$jam')";
  $datas = $mysqli->query($sql1);

}


if ($idHardware == "smp000090") {
 
  $sql  = "INSERT INTO `sems`(`id`, `amp`, `volt`, `power`, `date`, `time`) 
  VALUES ('smp000088','$l1','$volt','$powerL1','$tanggal','$jam')";
  $mysqli->query($sql);

  $sql1  = "INSERT INTO `sems`(`id`, `amp`, `volt`, `power`, `date`, `time`) 
  VALUES ('smp000089','$l2','$volt','$powerL2','$tanggal','$jam')";
  $mysqli->query($sql1);

  $sql2  = "INSERT INTO `sems`(`id`, `amp`, `volt`, `power`, `date`, `time`) 
  VALUES ('$idHardware','$l3','$volt','$powerL3','$tanggal','$jam')";
  $mysqli->query($sql2);
}  
        // if($datas){
        //     // $kode = "1";
        //     // $pesan = "Berhasil";
        //     $sems = $mysqli->query("SELECT * FROM `sems` WHERE `id` ='$idHardware' ORDER BY `date` DESC");
            
        //     $response = array();
        //     $response["data"] = array();
        //     while($tableSems = $sems->fetch_array()){
        //     $res['ID Hardware'] = $tableSems['id'];
        //     $res['STACK'] = $tableSems['stack'];
        //     $res['AMPER'] = $tableSems['amp'];
        //     $res['VOLT'] = $tableSems['volt'];
        //     $res['POWER'] = $tableSems['power'];
        //     $res['TANGGAL'] = $tableSems['date'];
        //     $res['JAM'] = $tableSems['time'];
        //     $res['STATUS'] = $tableSems['status'];
            
        //     array_push($response["data"], $res);
            
        //     }
        //   echo json_encode($response, JSON_UNESCAPED_SLASHES);
        // }
        // else{
          
        //     $kode = "0";
        //     $pesan = "Gagal";
        //     $response = array();
        //     $response["data"] = array();
        //         $h["kode"] = $kode;
        //         $h["pesan"] = $pesan;
        //         array_push($response["data"], $h);
        //     echo json_encode($response, JSON_UNESCAPED_SLASHES);
        //     echo "Error: " . $sql . "<br>" . $conn->error;
        // }
        // $response = array();
        // $response["data"] = array();
        //     $h["kode"] = $kode;
        //     $h["pesan"] = $pesan;
        //     array_push($response["data"], $h);
        // echo json_encode($response, JSON_UNESCAPED_SLASHES);
// }

// $conn->close();
