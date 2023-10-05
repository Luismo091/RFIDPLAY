<?php
if (isset($_POST['idsensor'])) {
    $idSensor = $_POST['idsensor'];

    include('\laragon\www\RFIDPLAY\main\conexion.php');

    $query = "DELETE FROM sensor WHERE idsensor = '$idSensor'";

    if ($mysqli->query($query) === TRUE) {
        echo "Registro eliminado correctamente";
    } else {
        echo "Error al eliminar el registro: " . $mysqli->error;
    }

    $mysqli->close();
}
?>
