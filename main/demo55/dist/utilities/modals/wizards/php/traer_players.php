<?php
include('\laragon\www\RFIDPLAY\main\conexion.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonFile = '../../demo55/dist/account/data.json';
    $jsonData = file_get_contents($jsonFile);
    $jsonData = json_decode($jsonData, true);

    // Obtén el último elemento del array JSON
    $lastEntry = end($jsonData);

    // Extrae el serial del nodoMCU
    $lastSerial = $lastEntry['serial'];

    // Consulta SQL para obtener los datos del jugador y el equipo al que pertenece
    $sql = "SELECT Nombre1, Ape1, e.id_escuela as idescuela, d.rfidcpde as idees 
            FROM jugadores j
            INNER JOIN documentos d ON j.id_documento = d.id_documento
            INNER JOIN escuelasdefutbol e ON j.id_escuela = e.id_escuela
            WHERE d.rfidcpde = :rfidcpde";
    $stmt = $mysqli->prepare($sql);
    $stmt->bindParam(':rfidcpde', $lastSerial, PDO::PARAM_STR);
    $stmt->execute();
    $playerData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Enviar los datos del jugador como respuesta JSON
    echo json_encode($playerData);
}