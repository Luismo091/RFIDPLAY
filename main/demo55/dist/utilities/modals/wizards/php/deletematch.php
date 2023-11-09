<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idPartido'])) {
    $idPartido = $_POST['idPartido'];

    // Establish a database connection (Assuming you have a database connection established)
    include('\laragon\www\RFIDPLAY\main\conexion.php');

    // Prepare and execute the DELETE statement
    $sql = "DELETE FROM partidos WHERE id_partido = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $idPartido);

    if ($stmt->execute()) {
        echo 'success'; // Return 'success' to indicate successful deletion
    } else {
        echo 'error'; // Return 'error' if the deletion failed
    }

    // Close the database connection (if not using a persistent connection)
    $mysqli->close();
} else {
    // Handle invalid or missing POST request
    echo 'error';
}
?>