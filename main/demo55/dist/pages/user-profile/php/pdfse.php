<?php
include('\laragon\www\RFIDPLAY\main\conexion.php');
if (isset($_POST['player_id'])) {
    $playerId = $_POST['player_id'];
    $sql = "SELECT docfile FROM documentos inner join rfidplay.jugadores j on documentos.id_documento = j.id_documento WHERE id_jugador = '$playerId'";
    $result = $mysqli->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pdfContent = $row['docfile'];
        $pdfUrl = 'data:application/pdf;base64,' . base64_encode($pdfContent);
        echo $pdfUrl;
    } else {
        echo 'No PDF data found for the player with ID ' . $playerId;
    }
} else {
    echo 'Invalid player ID.';
}
?>