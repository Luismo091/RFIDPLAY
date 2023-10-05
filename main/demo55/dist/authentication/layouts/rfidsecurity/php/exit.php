<?php
session_start();

// Destruir todas las variables de sesión.
$_SESSION = array();

// Si deseas destruir la cookie que almacena la sesión, descomenta las siguientes líneas.
// Nota: Esto eliminará la sesión, y no solo los datos de la sesión.
// if (ini_get("session.use_cookies")) {
//     $params = session_get_cookie_params();
//     setcookie(session_name(), '', time() - 42000,
//         $params["path"], $params["domain"],
//         $params["secure"], $params["httponly"]
//     );
// }


session_destroy();

header("Location: ../sign-in.html");
exit();
?>