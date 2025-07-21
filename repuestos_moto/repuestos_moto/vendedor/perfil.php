<?php
include '../config/config.php';
include 'verificar_sesion.php';
?>
<link rel="stylesheet" href="../css/estilos.css">

<h2>Mi Perfil</h2>
<p>Usuario: <?= $_SESSION['usuario'] ?></p>
<p>Rol: <?= $_SESSION['rol'] ?></p>
<a href="../login/logout.php">Cerrar sesión</a>