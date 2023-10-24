<?php
include('\laragon\www\RFIDPLAY\main\conexion.php');
// Obtiene el ID del partido del parámetro POST
$partidoId = $_POST['partidoId'];

// Inicializa un arreglo para almacenar la información de los equipos
$response = array();

// Consulta para obtener la información del equipo 1
$sqlEquipo1 = "SELECT equipos.id_escuela, equipos.nombre_equipo, escuelasdefutbol.nombre_escuela, escuelasdefutbol.fotoes
               FROM equipos
               INNER JOIN escuelasdefutbol ON equipos.id_escuela = escuelasdefutbol.id_escuela
               WHERE equipos.id_equipo = (SELECT id_equipo_local FROM partidos WHERE id_partido = $partidoId)";
$resultEquipo1 = $mysqli->query($sqlEquipo1);

if ($resultEquipo1->num_rows > 0) {
    $rowEquipo1 = $resultEquipo1->fetch_assoc();
    $response['equipo1']['idsesc'] = $rowEquipo1['id_escuela'];
    $response['equipo1']['nombre'] = $rowEquipo1['nombre_equipo'];
    $response['equipo1']['escuela'] = $rowEquipo1['nombre_escuela'];
    $response['equipo1']['imagen'] = base64_encode($rowEquipo1['fotoes']);
}

// Consulta para obtener la información del equipo 2
$sqlEquipo2 = "SELECT equipos.id_escuela, equipos.nombre_equipo, escuelasdefutbol.nombre_escuela, escuelasdefutbol.fotoes
               FROM equipos
               INNER JOIN escuelasdefutbol ON equipos.id_escuela = escuelasdefutbol.id_escuela
               WHERE equipos.id_equipo = (SELECT id_equipo_visitante FROM partidos WHERE id_partido = $partidoId)";
$resultEquipo2 = $mysqli->query($sqlEquipo2);

if ($resultEquipo2->num_rows > 0) {
    $rowEquipo2 = $resultEquipo2->fetch_assoc();
    $response['equipo2']['idsesc'] = $rowEquipo2['id_escuela'];
    $response['equipo2']['nombre'] = $rowEquipo2['nombre_equipo'];
    $response['equipo2']['escuela'] = $rowEquipo2['nombre_escuela'];
    $response['equipo2']['imagen'] = base64_encode($rowEquipo2['fotoes']);
}

// Devuelve la información como respuesta JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
