<?php

include('\laragon\www\RFIDPLAY\main\conexion.php');

if (isset($_POST['school_id'])) {
    $school_id = $_POST['school_id'];

    $query = "SELECT id_equipo, nombre_equipo FROM equipos WHERE id_escuela = $school_id";
    $result = mysqli_query($mysqli, $query);

    $query_school_image = "SELECT fotoes FROM escuelasdefutbol WHERE id_escuela = $school_id";
    $result_school_image = mysqli_query($mysqli, $query_school_image);
    $row_school_image = mysqli_fetch_assoc($result_school_image);
    $school_image = base64_encode($row_school_image['fotoes']);

    $response = array();
    $response['teams'] = array();
    $response['school_image'] = $school_image;

    while ($row = mysqli_fetch_assoc($result)) {
        $response['teams'][] = array(
            'id' => $row['id_equipo'],
            'name' => $row['nombre_equipo']
        );
    }

    echo json_encode($response);
} else {
    echo "??? No hay datos para esta escuela! ";
}
?>
