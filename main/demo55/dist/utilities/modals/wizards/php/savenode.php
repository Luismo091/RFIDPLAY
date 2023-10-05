<?php
session_start();
include('\laragon\www\RFIDPLAY\main\conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uniqueCode = $_POST['uniqueCode'];
    $selectedUserId = $_POST['selectedUserId'];
    $selectedCampoId = $_POST['selectedCampoId'];

    $query = "INSERT INTO sensor (sensoruid, iduserfk, idcampoa) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sii", $uniqueCode, $selectedUserId, $selectedCampoId);

    if ($stmt->execute()) {
        echo
        "<script>
        Swal.fire({
            text: 'Datos Recibidos',
            icon: 'success',
            buttonsStyling: false,
            confirmButtonText: 'Entendido',
            customClass: {
                confirmButton: 'btn btn-primary'
            }
        }).then(function() {
            // Cierra el modal al hacer clic en 'Entendido'
            $('#kt_modal_create_account').modal('hide');
            
            // Reinicia el contenido del modal cuando se cierra
            $('#kt_modal_create_account').on('hidden.bs.modal', function () {
                location.reload(); // Recarga la página para reiniciar el modal
            });
        });
    </script>";
    }
}
?>