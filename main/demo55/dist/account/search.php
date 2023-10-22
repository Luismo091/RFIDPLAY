<?php
include('\laragon\www\RFIDPLAY\main\conexion.php');
$serial = isset($_GET["serial"]) ? $mysqli->real_escape_string($_GET["serial"]) : "";
$nodeMCUId = isset($_GET["nodeMCUId"]) ? $mysqli->real_escape_string($_GET["nodeMCUId"]) : "";
// Consulta preparada
$stmt = $mysqli->prepare("SELECT * FROM trusted_sensors WHERE trusted_sensorscol = ?");
$stmt->bind_param("s", $nodeMCUId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 0) {
    $date = date("Y-m-d H:i:s");
    $data = array(
        "date" => $date,
        "serial" => $serial,
        "nodeMCUId" => $nodeMCUId
    );

    // Ruta segura para el archivo JSON (ajusta la ruta según tu configuración)
    $jsonFile = "data.json";

    if (file_exists($jsonFile) && is_writable($jsonFile)) {
        // Leer el archivo JSON existente si existe
        $currentData = array();
        if (file_exists($jsonFile)) {
            $jsonData = file_get_contents($jsonFile);
            $currentData = json_decode($jsonData, true);
        }

        $currentData[] = $data;
        $newJsonData = json_encode($currentData, JSON_PRETTY_PRINT);
        file_put_contents($jsonFile, $newJsonData);

        // Responder al cliente
        printf("R-AIR Serial: %s Recibido", $serial);
    } else {
        printf("Error: No se pudo acceder al archivo JSON.");
    }
} else {
    printf("Error: No se pudo guardar el serial.");
}
