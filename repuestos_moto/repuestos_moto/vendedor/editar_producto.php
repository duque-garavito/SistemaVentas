<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'vendedor') {
    header("Location: ../login/login.php");
    exit();
}

include '../config/config.php';
include 'verificar_sesion.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$producto = $resultado->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    $update = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=? WHERE id=?");
    $update->bind_param("ssdii", $nombre, $descripcion, $precio, $stock, $id);
    $update->execute();
    header("Location: productos.php");
    exit();
}
?>
<link rel="stylesheet" href="../css/estilos.css">
<?php include '../includes/header.php'; ?>
<h2>Editar Producto</h2>
<form method="POST">
    Nombre: <input type="text" name="nombre" value="<?= $producto['nombre'] ?>" required><br>
    Descripción: <textarea name="descripcion" required><?= $producto['descripcion'] ?></textarea><br>
    Precio: <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required><br>
    Stock: <input type="number" name="stock" value="<?= $producto['stock'] ?>" required><br>
    <input type="submit" value="Actualizar">
</form>