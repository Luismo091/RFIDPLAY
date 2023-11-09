<?php
include('C:\laragon\www\RFIDPLAY\main\conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $a_soccer_name = $_POST['a_soccer_name'];
    $a_adress = $_POST['a_adress'];
    $a_city = $_POST['a_city'];

    // Check
    $e_shield_f = isset($_FILES['a_shield_f']) ? $_FILES['a_shield_f'] : null;

    $response = array(); // Crear un array para la respuesta

    if ($e_shield_f) {
        $e_selectedImageData = addslashes(file_get_contents($e_shield_f['tmp_name']));
        $sql = "INSERT INTO camposdejuego (id_campo,nombre_campo,direccion,ciudad,fotoblob)  
                VALUES (null,'$a_soccer_name','$a_adress','$a_city','$e_selectedImageData')";
        if ($mysqli->query($sql) === TRUE) {
            $response['success'] = true; // Indicar que la inserción fue exitosa
            $response['message'] = 'Los datos del escenario se insertaron correctamente.';
        } else {
            $response['success'] = false; // Indicar que la inserción falló
            $response['message'] = 'Error: ' . $mysqli->error;
        }
    } else {
        $response['success'] = false; // Indicar que la inserción falló
        $response['message'] = 'Error: Faltan archivos';
    }

    // Devolver la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}

?>
