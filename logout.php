<?php
session_start();
// Destruimos todas las variables de la sesión
$_SESSION = array();
session_destroy();

// Redireccionamos de vuelta al Login
header("Location: login.php");
exit;
?>
