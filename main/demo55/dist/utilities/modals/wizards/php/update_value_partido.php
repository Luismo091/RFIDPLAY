<?php
include('\laragon\www\RFIDPLAY\main\conexion.php');

// Check if 'partidoId' is set in the POST request
if (isset($_POST['partidoId'])) {
    $partidoId = $_POST['partidoId'];
    error_log("Solicitud AJAX recibida para partidoId: " . $partidoId);

    // Prepare and execute the SQL query
    $sqlEquipo1 = "UPDATE partidos SET estado = '1' WHERE id_partido = $partidoId";
    $resultEquipo1 = $mysqli->query($sqlEquipo1);

    if ($resultEquipo1) {
        // Query was executed successfully
        echo json_encode(array('success' => true, 'message' => 'Partido iniciado con éxito.'));

    } else {
        // Query failed
        echo json_encode(array('success' => false, 'message' => 'Error al iniciar el partido.'));
    }
} else {
    // 'partidoId' is not set in the POST request
    echo json_encode(array('success' => false, 'message' => 'No se proporcionó el ID del partido.'));
}
