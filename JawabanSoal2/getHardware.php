<?php
include "config.php";
global $mysqli;

$qartikel = $mysqli->query("SELECT * FROM hardware ");

$response = array();
$response["data"] = array();
while ($x = mysqli_fetch_array($qartikel)) {
	
	$h["ID"]	= $x["id"];
    $h["STACK"] = $x["stack"];
    $h["STATUS"]	= $x["status"];
  
    array_push($response["data"], $h);

}
echo json_encode($response, JSON_UNESCAPED_SLASHES);

?>