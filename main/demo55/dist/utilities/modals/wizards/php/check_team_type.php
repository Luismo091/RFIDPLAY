<?php
include('\laragon\www\RFIDPLAY\main\conexion.php');

if ($mysqli->connect_error) {
    die("La conexión falló: " . $mysqli->connect_error);
}

// Get the selected team ID from the AJAX request
$teamId = $_POST["teamId"];

$query = "SELECT fk_type_equipo FROM equipos WHERE id_equipo = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $teamId);
$stmt->execute();
$result = $stmt->get_result();

$response = array();

if ($row = $result->fetch_assoc()) {
    // Check if the team is of type 2
    $response["isTypeTwo"] = ($row["fk_type_equipo"] == 2);
} else {
    // If the team is not found, assume it's not type 2
    $response["isTypeTwo"] = false;
}

// Send the response as JSON
header("Content-Type: application/json");
echo json_encode($response);
