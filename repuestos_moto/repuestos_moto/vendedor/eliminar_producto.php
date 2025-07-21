<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'vendedor') {
    header("Location: ../login/login.php");
    exit();
}

include '../config/config.php';
include 'verificar_sesion.php';

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: productos.php");
exit();