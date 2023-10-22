<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");
session_start();
include('\laragon\www\RFIDPLAY\main\conexion.php');
if (empty($_POST['email']) || empty($_POST['password'])) {
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
    $USUARIO = $_POST['email'];
    $PASSWORD = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT * FROM usuarios INNER JOIN rfidplay.documentos d 
    ON usuarios.docidfk = d.id_documento WHERE mail = ? AND contraseña = ?");
    $stmt->bind_param("ss", $USUARIO, $PASSWORD);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($datos = $result->fetch_object()) {

        $_SESSION['mail'] = $datos->numero_documento;
        $_SESSION['idusu'] = $datos->idusuarios;
        $_SESSION['docu'] = $datos->numero_documento;
        $_SESSION['foto'] = $datos->documentosfoto;
        $nombreCompleto = $datos->Nombre1 . " " . $datos->Nombre2 . " " . $datos->Ape1 . " " . $datos->Ape2;
        $nombreCompleto2 = $datos->Nombre1 . "  " . $datos->Ape1;
        $_SESSION['nombredeusu'] = $nombreCompleto;
        $_SESSION['nombredeusu2'] = $nombreCompleto2;

        if ($datos->roles == 2) {
            $sql = $mysqli->query("SELECT nombre_escuela as nes ,e.id_escuela as escuela, fotoes FROM usuarios INNER JOIN rfidplay.documentos d
                                  ON usuarios.docidfk = d.id_documento 
    INNER JOIN rfidplay.entrenadores e on d.id_documento = e.id_documento 
    INNER JOIN rfidplay.escuelasdefutbol e2 on e.id_escuela = e2.id_escuela WHERE idusuarios =$datos->idusuarios;");

            if ($datos = $sql->fetch_object()) {
            $_SESSION['idescuela'] = $datos->escuela;
            $_SESSION['Nombreescu'] = $datos->nes;
            $_SESSION['fotoes'] = $datos->fotoes;
        }
        }
            echo
            "<script>
    Swal.fire({
        text: 'RFIDPLAY le da la Bienvenida $nombreCompleto!',
        icon: 'success',
        buttonsStyling: false,
        confirmButtonText: '¡Entremos!',
        customClass: {
            confirmButton: 'btn btn-primary'
        }
    }).then(function() {
        window.location.href = '../../demo55/dist/dashboards/user-dashboard.php';
    });
    </script>";
        } else {
            echo
            "<script>
        Swal.fire({
            text: 'La contraseña o el mail es incorrecta. Por favor, revísala y vuelve a intentarlo.',
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