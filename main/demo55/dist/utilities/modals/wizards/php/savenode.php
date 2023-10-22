<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");
session_start();
include('\laragon\www\RFIDPLAY\main\conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $uniqueCode = $_POST['uniqueCode'];
    $selectedUserId = $_POST['selectedUserId'];
    $selectedCampoId = $_POST['selectedCampoId'];

    $checkQuery = "SELECT COUNT(*) FROM trusted_sensors WHERE trusted_sensorscol = ?";
    $checkStmt = $mysqli->prepare($checkQuery);
    $checkStmt->bind_param("s", $uniqueCode);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();

    $checkStmt->close();

    if ($count > 0) {
        $checkSensorQuery = "SELECT COUNT(*) FROM sensor WHERE sensoruid = ?";
        $checkSensorStmt = $mysqli->prepare($checkSensorQuery);
        $checkSensorStmt->bind_param("s", $uniqueCode);
        $checkSensorStmt->execute();
        $checkSensorStmt->bind_result($sensorCount);
        $checkSensorStmt->fetch();

        $checkSensorStmt->close();

        if ($sensorCount === 0) {
            // Sensor with the same uniqueCode is not registered, proceed with insertion
            $insertQuery = "INSERT INTO sensor (sensoruid, iduserfk, idcampoa) VALUES (?, ?, ?)";
            $insertStmt = $mysqli->prepare($insertQuery);
            $insertStmt->bind_param("sii", $uniqueCode, $selectedUserId, $selectedCampoId);

            if ($insertStmt->execute()) {
                echo "
                <script>
                    Swal.fire({
                        text: 'Datos Recibidos',
                        icon: 'success',
                        buttonsStyling: false,
                        confirmButtonText: 'Entendido',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        }
                    }).then(function() {
                        // Cierra the modal al hacer clic en 'Entendido'
                        $('#kt_modal_create_account').modal('hide');
                        
                        // Reinicia el contenido del modal cuando se cierra
                        $('#kt_modal_create_account').on('hidden.bs.modal', function () {
                            location.reload(); // Recarga la página para reiniciar el modal
                        });
                    });
                </script>";
            }
        } else {
            echo "
            <script>
                Swal.fire({
                    text: 'El Sensor que estás registrando ya existe.',
                    icon: 'error',
                    buttonsStyling: false,
                    confirmButtonText: 'Entendido',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            </script>";
        }
    } else {
        echo "
        <script>
            Swal.fire({
                text: 'Este ID de sensor no esta verificado, asegurate que esta ingresando un id verificado. ',
                icon: 'info',
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
