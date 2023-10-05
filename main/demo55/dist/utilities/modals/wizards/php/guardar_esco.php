<?php
include('\laragon\www\RFIDPLAY\main\conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $playerName = $_POST['playerName'];
    $selectedEscuela = $_POST['selectedEscuela'];
    $selectedEquipos = json_decode($_POST['selectedEquipos']);
    $rfidSensor = $_POST['rcode'];
    $selectedFile = $_FILES['selectedFile'];
    $selectedImage = $_FILES['selectedImage'];

    $documentotype = $_POST['tipodoc'];
    $documentonum = $_POST['numdoc'];

    // Separar el nombre completo por espacios.
    $names = explode(' ', $playerName);

// Obtener el primer nombre.
    $nombre1 = $names[0];

// Obtener el segundo nombre, si lo hay.
    if (count($names) > 2) {
        $nombre2 = $names[1];
    } else {
        $nombre2 = '';
    }

// Obtener el apellido paterno.
    $ape1 = $names[count($names) - 2];

// Obtener el apellido materno.
    $ape2 = $names[count($names) - 1];

    // Obtener los contenidos de la imagen y el archivo
    $selectedFileData = addslashes(file_get_contents($selectedFile['tmp_name']));
    $selectedImageData = addslashes(file_get_contents($selectedImage['tmp_name']));

    // Insertar datos en la base de datos
    $sql = "INSERT INTO documentos (tipo_documento, numero_documento, Nombre1, Nombre2, Ape1, Ape2, docfile, documentosfoto) 
            VALUES ('$documentotype', '$documentonum', '$nombre1', '$nombre2', '$ape1', '$ape2', '$selectedFileData', '$selectedImageData')";

    if ($mysqli->query($sql) === TRUE) {
        $lastInsertedId = $mysqli->insert_id; // Get the last inserted ID
        // Insertar datos en la tabla jugadores
        if ($mysqli->query($sql) === TRUE) {
            $lastInsertedId = $mysqli->insert_id; // Get the last inserted ID
            $selectedEscuela = $_POST['selectedEscuela']; // Assuming 'selectedEscuela' is a value to be inserted

            // Insert initial record into the 'jugadores' table
            $stmtInitial = $mysqli->prepare("INSERT INTO jugadores (id_documento, id_escuela) VALUES (?, ?)");
            $stmtInitial->bind_param("ii", $lastInsertedId, $selectedEscuela);
            $stmtInitial->execute();
            $stmtInitial->close();

            // Insert record into the 'jugador_equipo' table
            $stmtJugadorEquipo = $mysqli->prepare("INSERT INTO jugador_equipo (equipofk, idjugadorfk) VALUES (?, ?)");
            $stmtJugadorEquipo->bind_param("ii", $equipo, $lastInsertedId); // Assuming 'equipofk' and 'idjugadorfk' are integers

            // Insert data into the 'jugador_equipo' table for each equipo
            $selectedEquipos = json_decode($_POST['selectedEquipos']);

            foreach ($selectedEquipos as $equipo) {
                $stmtJugadorEquipo->execute();
            }

            $stmtJugadorEquipo->close();

            echo "OK";
        } else {
            echo "Error al insertar datos en la base de datos: " . $mysqli->error;
        }


    } else {

    }
    echo "Error: Método de solicitud no válido";
}
?>