<?php
if (isset($_POST['idsensor'])) {
    $idSensor = $_POST['idsensor'];
    include('\laragon\www\RFIDPLAY\main\conexion.php');
    $query = "DELETE FROM sensor WHERE idsensor = $idSensor";
    if ($mysqli->query($query) === TRUE) {
        // Muestra la SweetAlert y reinicia la página
        echo '<script>
            Swal.fire({
                title: "Eliminado'.$idSensor.'",
                text: "El sensor se ha eliminado correctamente.",
                icon: "success",
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                location.reload(); // Recarga la página después de 1.5 segundos
            });
        </script>';
    } else {
        // Muestra la SweetAlert con un mensaje de error
        echo '<script>
            Swal.fire({
                title: "Error",
                text: "Error al eliminar el registro: ' . $mysqli->error . '",
                icon: "error"
            });
        </script>';
    }
}
?>