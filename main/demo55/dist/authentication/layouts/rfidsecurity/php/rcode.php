<?php
session_start();
include('\laragon\www\RFIDPLAY\main\conexion.php');

if (empty($_POST['code_1']) || empty($_POST['code_2']) || empty($_POST['code_3']) || empty($_POST['code_4'])|| empty($_POST['code_5'])|| empty($_POST['code_6'])) {
    echo
    "<script>
        Swal.fire({
            text: 'Hay algunos campos vacios',
            icon: 'warning',
            buttonsStyling: false,
            confirmButtonText: 'Volver a intentarlo',
            customClass: {
                confirmButton: 'btn btn-primary'
            }
        });
        </script>";
} else {
    $combinedValues = $_POST['code_1'] . $_POST['code_2'] . $_POST['code_3'] . $_POST['code_4'] . $_POST['code_5'] . $_POST['code_6'];
    $sql = $mysqli->query("SELECT e.nombre_equipo as eq1,i.nombre_equipo as eq2 FROM partidos INNER JOIN rfidplay.equipos e on partidos.id_equipo_local = e.id_equipo INNER JOIN rfidplay.equipos i on partidos.id_equipo_visitante = i.id_equipo
inner join rfidplay.camposdejuego c on partidos.idcampojuego = c.id_campo join rfidplay.torneos t on t.id_torneo = partidos.id_torneo where rcode ='$combinedValues'");

    if ($datos = $sql->fetch_object()) {
        $eq1 = $datos->eq1;
        $eq2 = $datos->eq2;
        echo
        "<script>
        Swal.fire({
            text: 'Codigo RPLAY encontrado partido $eq1 Vs $eq2',
            icon: 'success',
            buttonsStyling: false,
            confirmButtonText: '¡Entremos!',
            customClass: {
                confirmButton: 'btn btn-primary'
            }
        });
        </script>";
    } else {
        echo
        "<script>
        Swal.fire({
            text: 'Lo sentimos, este codigo no esta disponible por ahora',
            icon: 'error',
            buttonsStyling: false,
            confirmButtonText: 'Entendido',
            customClass: {
                confirmButton: 'btn btn-primary'
            }
        });
        </script>";
    }
}
?>