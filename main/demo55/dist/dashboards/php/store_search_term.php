<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");
session_start();

if(isset($_POST['term'])) {
    $clickedTerm = $_POST['term'];

    $_SESSION['search_terms'][] = $clickedTerm;

    echo 'Término almacenado en sesión: ' . $clickedTerm;
} else {
    echo 'Error: Término no recibido.';
}
?>
