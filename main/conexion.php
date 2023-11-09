<?php
$hostname = "rfidplya-datacenter.mysql.database.azure.com";
$username = "rfidMaster";
$password = "\$SecureMySQL2023@";
$database = "rfidplay";

$start_time = microtime(true);

$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Error en la conexión: " . $mysqli->connect_error);
}
$end_time = microtime(true);
// Calcular la latencia
$latency = round(($end_time - $start_time) * 1000, 2); // Convertir a milisegundosS
?>
