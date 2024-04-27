<?php

include "config.php";

$id = $_GET['id'];
$idH = $_GET['idH'];
echo $id." ".$idH;

if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}
if($id == '1'){
    echo $id;
    $sql  = "UPDATE `hardware` SET `status`='off' WHERE id != 'smp000090'";
    $datas = $mysqli->query($sql);
}
if($id == 3){
    echo $id;
    $sql  = "UPDATE `hardware` SET `status`='off' WHERE id = 'smp000090'";
    $datas = $mysqli->query($sql);
}
if($id == 5){
    echo $id;
    $sql  = "UPDATE `hardware` SET `status`='on' WHERE id = '$idH'";
    $datas = $mysqli->query($sql);
}
if($id == 6){
    echo $id;
    $sql  = "UPDATE `hardware` SET `status`='off' WHERE id = '$idH'";
    $datas = $mysqli->query($sql);
}
        



?>