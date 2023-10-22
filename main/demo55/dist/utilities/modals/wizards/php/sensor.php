<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");
session_start();
include('\laragon\www\RFIDPLAY\main\conexion.php');
$nodeMCUId = $_GET["nodeMCUId"];
$serial = $_GET["serial"];
$idsus=$_SESSION['idusu'];

$sql = "SELECT sensoruid,iduserfk FROM sensor INNER JOIN rfidplay.usuarios u on sensor.iduserfk = u.idusuarios 
                          WHERE  sensoruid ='$nodeMCUId' and iduserfk = '$idsus'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $serial;
} else {
    echo "¡NO HAY SENSORES REGISTRADOS!";
}

?>
