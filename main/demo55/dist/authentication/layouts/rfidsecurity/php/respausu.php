<?php
include('\laragon\www\RFIDPLAY\main\conexion.php');

function ocultarNombre($nombreCompleto) {
    $palabras = explode(" ", $nombreCompleto);
    $nombreOculto = "";

    foreach ($palabras as $palabra) {
        if (strlen($palabra) > 2) {
            $nombreOculto .= $palabra[0] . str_repeat("*", strlen($palabra) - 2) . " ";
        } else {
            $nombreOculto .= $palabra . " ";
        }
    }

    return trim($nombreOculto);
}
if (empty($_POST['cc'])) {
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
    $USUARIO = $_POST['cc'];

    $sql = $mysqli->query("SELECT * FROM usuarios INNER JOIN rfidplay.documentos d 
    ON usuarios.docidfk = d.id_documento WHERE numero_documento ='$USUARIO'");
    if ($datos = $sql->fetch_object()) {
        $ndoc = $datos->mail;
        $nombreCompleto = "$datos->Nombre1" . $datos->Nombre2 . " " . $datos->Ape1 . " " . $datos->Ape2;


        $nombreCompleto = $datos->Nombre1 . " " . $datos->Nombre2 . " " . $datos->Ape1 . " " . $datos->Ape2;
        $nombreOculto = ocultarNombre($nombreCompleto);
        echo
        "<script>
        Swal.fire({
            text: 'Hola $nombreOculto este es tu usuario $ndoc',
            icon: 'success',
            buttonsStyling: false,
            confirmButtonText: '¡Genial!',
            customClass: {
                confirmButton: 'btn btn-primary'
            }
        });
        </script>";
    } else {
        echo
        "<script>
        Swal.fire({
            text: 'Revisa tu número de documento y vuelve a intentarlo asegurate que ya te encuentres registrado',
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