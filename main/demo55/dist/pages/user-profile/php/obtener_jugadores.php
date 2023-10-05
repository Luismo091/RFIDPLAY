<?php
include('conexion.php');
session_start();
include('\laragon\www\RFIDPLAY\main\conexion.php');

$jugadoresPorPagina = 5;
$paginaActual = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($paginaActual - 1) * $jugadoresPorPagina;

$sql = "SELECT * FROM jugadores LIMIT $offset, $jugadoresPorPagina";
$resultado = $mysqli->query($sql);

if ($resultado->num_rows > 0) {
    // Construir tarjetas HTML para los jugadores
    while ($row = $resultado->fetch_assoc()) {
        // Construir tarjetas de perfil de jugadores aquí
        echo '<div class="col-md-6 col-xxl-4">';
        // Agregar contenido de la tarjeta
        // ...
        echo '</div>';
    }
} else {
    // No hay más jugadores para mostrar
    echo 'No hay más jugadores disponibles.';
}

$mysqli->close();
?>