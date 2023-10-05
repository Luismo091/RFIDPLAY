<?php
global $mysqli;
include('\laragon\www\RFIDPLAY\main\conexion.php');
// Obtener los datos del formulario
$selectedSchool1 = $_POST["selectedSchool1"];
$selectedSchool2 = $_POST["selectedSchool2"];
$selectedDate = $_POST["selectedDate"];
$selectedCampo = $_POST["selectedCampo"];
$selectedTeams1 = json_decode($_POST["selectedTeams1"]);
$selectedTeams2 = json_decode($_POST["selectedTeams2"]);


$consulta = "INSERT INTO partidos (id_partido,id_torneo,id_equipo_local, id_equipo_visitante, fecha,idcampojuego )
VALUES (,null,'1','$selectedTeams1', '$selectedTeams2', '$selectedDate', '$selectedCampo')";

if (mysqli_query($mysqli, $consulta)) {
echo "Datos insertados correctamente";
} else {
echo "Error al insertar datos: " . mysqli_error($mysqli);
}
?><?php
