<?php
$hostname = "rfidplya-datacenter.mysql.database.azure.com";
$username = "rfidMaster";
$password = "\$SecureMySQL2023@";
$database = "rfidplay";

$start_time = microtime(true);

$mysqli = mysqli_init();
mysqli_ssl_set($mysqli, NULL, NULL, "{}//DigiCertGlobalRootCA.crt.pem", NULL, NULL);

// Realizar la conexión segura con SSL
if (!mysqli_real_connect($mysqli, $hostname, $username, $password, $database, 3306, MYSQLI_CLIENT_SSL)) {
    die("Error en la conexión: " . mysqli_connect_error());
}
$end_time = microtime(true);
// Calcular la latencia
$latency = round(($end_time - $start_time) * 1000, 2); // Convertir a milisegundosS
?>
