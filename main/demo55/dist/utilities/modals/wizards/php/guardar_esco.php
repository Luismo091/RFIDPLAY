<?php
include('C:\laragon\www\RFIDPLAY\main\conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $a_soccer_name = $_POST['a_soccer_name'];
    $a_adress = $_POST['a_adress'];
    $a_city = $_POST['a_city'];

    $e_s_name = $_POST['e_s_name'];
    $e_tipodoc = $_POST['e_tipodoc'];
    $e_identificationcode = $_POST['e_identificationcode'];
    $ee_mail = $_POST['ee_mail'];
    $ee_pass = $_POST['ee_pass'];
    $e_rfidsensro = $_POST['e_rfidsensro'];

    // Check
    $e_shield_f = isset($_FILES['e_shield_f']) ? $_FILES['e_shield_f'] : null;

    // Check if 'e_doc_pdf'
    $e_doc_pdf = isset($_FILES['e_doc_pdf']) ? $_FILES['e_doc_pdf'] : null;

    // Check if 'a_shield_f'
    $a_shield_f = isset($_FILES['a_shield_f']) ? $_FILES['a_shield_f'] : null;

    // Separar el nombre completo por espacios.
    $names = explode(' ', $e_s_name);
    $nombre1 = $names[0];
    $nombre2 = count($names) > 1 ? $names[1] : '';
    $ape1 = $names[count($names) - 2];
    $ape2 = $names[count($names) - 1];

    if ($e_shield_f && $e_doc_pdf) {
        // Obtener los contenidos de la imagen y el archivo del representante legal
        $e_selectedFileData = addslashes(file_get_contents($e_doc_pdf['tmp_name']));
        $e_selectedImageData = addslashes(file_get_contents($e_shield_f['tmp_name']));

        // Obtener la imagen de la escuela
        $ee_selectedImageData = addslashes(file_get_contents($a_shield_f['tmp_name']));

        $sql = "INSERT INTO escuelasdefutbol (nombre_escuela, direccion, ciudad, fotoes)  
                VALUES ('$a_soccer_name','$a_adress','$a_city','$ee_selectedImageData')";

        if ($mysqli->query($sql) === TRUE) {
            $lastInsertedId = $mysqli->insert_id;
            $sqlD = "INSERT INTO documentos (tipo_documento, numero_documento, Nombre1, Nombre2, Ape1, Ape2, documentosfoto, docfile,rfidcpde) 
                VALUES ('$e_tipodoc','$e_identificationcode','$nombre1','$nombre2','$ape1','$ape2','$e_selectedFileData','$e_selectedImageData','$e_rfidsensro')";
            if ($mysqli->query($sqlD) === TRUE) {
                $lastInsertedIdd = $mysqli->insert_id;
                $sqlu = "INSERT INTO usuarios (mail, contraseña, docidfk, roles)
                VALUES ('$ee_mail',' $ee_pass','$lastInsertedIdd','2')";
                if ($mysqli->query($sqlu) === TRUE) {
                    $lastInsertedusu = $mysqli->insert_id;
                    $sqle = "INSERT INTO entrenadores (id_escuela, usuariofk, id_documento)
                VALUES ('$lastInsertedId','$lastInsertedusu','$lastInsertedIdd')";
                    if ($mysqli->query($sqle) === TRUE) {
                        echo 'Insertado todo con exito :)';
                    }

                }

            }

        } else {
            echo 'Error: ' . $mysqli->error;
        }
    } else {
        echo 'Error: Missing files';
    }
}

?>
