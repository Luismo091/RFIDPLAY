<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "rfidplay";

$start_time = microtime(true);

$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Error en la conexión: " . $mysqli->connect_error);
}
$end_time = microtime(true);

// Calcular la latencia
$latency = round(($end_time - $start_time) * 1000, 2); // Convertir a milisegundos
?>
