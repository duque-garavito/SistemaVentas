<?php
session_start();         // Inicia la sesión para poder destruirla
session_unset();         // Elimina todas las variables de sesión
session_destroy();       // Destruye la sesión completamente

// Redirige al login
header("Location: login.php");
exit();
?>