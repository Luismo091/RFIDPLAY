<?php
include('\laragon\www\RFIDPLAY\main\conexion.php');
// Recibir los datos por POST
$escuela1 = $_POST['select-school-1'];
$equipo1 = $_POST['select-teams-1'];
$escuela2 = $_POST['select-school-2'];
$equipo2 = $_POST['select-teams-2'];
$campo = $_POST['campoe'];
$date = $_POST['kt_datepicker_3'];

// Genera un código rcode de 6 dígitos aleatorios
$rcode = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
// Validar los datos y mostrar un mensaje según el resultado
if ($escuela1 && $equipo1 && $escuela2 && $equipo2 && $campo && $date) {
    // Insertar los datos en la tabla partidos
    $query = "INSERT INTO partidos (id_partido, id_equipo_local, id_equipo_visitante, idcampojuego, fecha, rcode, estado, fecha_fi, hora_fi) VALUES (NULL, $equipo1, $equipo2, $campo, '$date', '$rcode', '0', NULL, NULL)";


    if ($mysqli->query($query) === true) {
        // Muestra un mensaje SweetAlert y recarga la página
        echo '<script>
        Swal.fire({
            html: `Los datos se han guardado correctamente.`,
            icon: "success",
            showCancelButton: false,
            confirmButtonText: "OK",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        }).then(function() {
            location.reload();
        });
      </script>';
    } else {
        // Muestra un mensaje de error con SweetAlert
        echo '<script>
        Swal.fire({
            html: `Hubo un problema al guardar los datos.`,
            icon: "error",
            showCancelButton: false,
            confirmButtonText: "OK",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
      </script>';
    }
} else {
    // Muestra un mensaje de error con SweetAlert
    echo '<script>
        Swal.fire({
            html: `Faltan datos obligatorios.`,
            icon: "error",
            showCancelButton: false,
            confirmButtonText: "OK",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
      </script>';
}

// Cierra la conexión a la base de datos
$mysqli->close();
?>
