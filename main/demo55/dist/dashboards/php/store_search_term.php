<?php
session_start();

if(isset($_POST['term'])) {
    $clickedTerm = $_POST['term'];

    $_SESSION['search_terms'][] = $clickedTerm;

    echo 'Término almacenado en sesión: ' . $clickedTerm;
} else {
    echo 'Error: Término no recibido.';
}
?>
