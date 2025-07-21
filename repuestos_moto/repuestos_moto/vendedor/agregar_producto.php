<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'vendedor') {
    header("Location: ../login/login.php");
    exit();
}

include '../config/config.php';
include 'verificar_sesion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $stock);
    $stmt->execute();
    header("Location: productos.php");
    exit();
}
?>
<link rel="stylesheet" href="../css/estilos.css">
<h2>Agregar Producto</h2>
<form method="POST">
    Nombre: <input type="text" name="nombre" required><br>
    Descripción: <textarea name="descripcion" required></textarea><br>
    Precio: <input type="number" step="0.01" name="precio" required><br>
    Stock: <input type="number" name="stock" required><br>
    <input type="submit" value="Guardar">
</form>