<?php
$serial = $_GET["serial"];
$nodeMCUId = $_GET["nodeMCUId"];

include('\laragon\www\RFIDPLAY\main\conexion.php');
$sql = $mysqli->query("SELECT * FROM trusted_sensors where trusted_sensorscol = '$nodeMCUId'");
if ($sql->num_rows != 0) {

    $date = date("Y-m-d H:i:s");
    $data = array(
        "date" => $date,
        "serial" => $serial,
        "nodeMCUId" => $nodeMCUId
    );

// Leer el archivo JSON existente si existe
    $jsonFile = "data.json";
    $currentData = array();
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $currentData = json_decode($jsonData, true);
    }

    $currentData[] = $data;
    $newJsonData = json_encode($currentData, JSON_PRETTY_PRINT);
    file_put_contents($jsonFile, $newJsonData);

// Responder al cliente
    printf("Serial %s saved", $serial);


}else{
    printf("YOU MAY VIOLATE THE SECURITY RULES OF RFIDPLAY (ERR: ESTE SENSOR NO ESTA REGISTRADO)");
}
