<?php

include('\laragon\www\RFIDPLAY\main\conexion.php');

if ($mysqli->connect_error) {
    die("La conexión falló: " . $mysqli->connect_error);
}

// Obtener el ID de la escuela seleccionada
$escuelaId = $_POST["escuelaId"];

// Consulta para obtener los equipos de la escuela seleccionada
$query = "SELECT id_equipo, nombre_equipo,fk_type_equipo FROM equipos WHERE id_escuela = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $escuelaId);
$stmt->execute();
$result = $stmt->get_result();

$equipos = array();
while ($row = $result->fetch_assoc()) {
    $equipos[] = $row;

    header("Content-Type: application/json");}

echo json_encode($equipos);
?>