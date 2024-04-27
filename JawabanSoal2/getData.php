<?php
include "config.php";
global $mysqli;
$hardwareID = $_GET['hardwareID'];



$qartikel = $mysqli->query("SELECT * FROM sems WHERE id = '$hardwareID' ORDER BY `date` DESC LIMIT 1");

$response = array();
$response["data"] = array();
while ($x = mysqli_fetch_array($qartikel)) {
	
    $h["NO"] = $x["no"];
	$h["ID"]	= $x["id"];
    $h["AMPER"] = $x["amp"];
	$h["VOLT"]	= $x["volt"];
    $h["POWER"] = $x["power"];
	$h["DATE"]	= $x["date"];
    $h["TIME"] = $x["time"];
   
  
    array_push($response["data"], $h);

}
echo json_encode($response, JSON_UNESCAPED_SLASHES);
// }else{
// 	$response = array();
//     $d["Status_code"]="5";
//     $d["status_message"]= "Invalid API key: Anda harus mempunyai api key untuk mengakse data ini";
// 	$d["success"]="FALSE";
//     array_push($response, $d);
//     echo json_encode($response,JSON_UNESCAPED_SLASHES);
// }

?>