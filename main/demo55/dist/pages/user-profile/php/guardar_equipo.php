<?php
include('C:\laragon\www\RFIDPLAY\main\conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $e_name_team = $_POST['e_name_team'];
    $e_type_team = $_POST['e_type_team'];
    $idsc = $_POST['esid'];
    echo 'DATOS RECIBIDOS POR JS________________________>';
    echo $e_name_team, $e_type_team, $idsc;

    $e_name_team = mysqli_real_escape_string($mysqli, $e_name_team);
    $e_type_team = intval($e_type_team);
    $idsc = intval($idsc);

    $sql = "INSERT INTO equipos (id_equipo, nombre_equipo, id_escuela, fk_type_equipo)  
            VALUES (null, '$e_name_team', $idsc, $e_type_team)";
    if ($mysqli->query($sql) === TRUE) {
        echo 'Perfecto';
    } else {
        echo 'Error: ' . $mysqli->error;
    }
}
?>

