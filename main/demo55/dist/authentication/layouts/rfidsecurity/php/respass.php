<?php
include('\laragon\www\RFIDPLAY\main\conexion.php');

function generateRandomPassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+[]{}|;:,.<>?';
    $password = '';

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = mt_rand(0, strlen($chars) - 1);
        $password .= $chars[$randomIndex];
    }

    return $password;
}
if (empty($_POST['email'])) {
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

    $sql = $mysqli->query("SELECT * FROM usuarios INNER JOIN rfidplay.documentos d 
    ON usuarios.docidfk = d.id_documento WHERE mail ='$USUARIO'");
    if ($datos = $sql->fetch_object()) {
        $correo = $datos->mail;
        $randomPassword = generateRandomPassword(12);
        echo
        "<script>
        Swal.fire({
            text: 'Se ha enviado un correo con la nueva contraseña a $correo con la nueva contraseña $randomPassword',
            icon: 'success',
            buttonsStyling: false,
            confirmButtonText: '¡Entremos!',
            customClass: {
                confirmButton: 'btn btn-primary'
            }
            }).then(function() {
        window.location.href = '../../demo55/dist/authentication/layouts/rfidsecurity/sign-in.html'; // Cambia 'otra_pagina.php' por la URL de la página a la que deseas redirigir
    });
   
        </script>";
    } else {
        echo
        "<script>
        Swal.fire({
            text: 'Revisa tu Mail y vuelve a intentarlo',
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