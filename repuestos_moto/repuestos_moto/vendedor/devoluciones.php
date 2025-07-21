<?php
include '../config/config.php';
include 'verificar_sesion.php';

// Insertar devolución
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_venta = $_POST['id_venta'];
    $id_producto = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];
    $motivo = $_POST['motivo'];

    // Insertar devolución
    $sql = "INSERT INTO devoluciones (id_venta, id_producto, cantidad, motivo) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $id_venta, $id_producto, $cantidad, $motivo);
    $stmt->execute();

    // Actualizar stock
    $sql = "UPDATE productos SET stock = stock + ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cantidad, $id_producto);
    $stmt->execute();

    header("Location: devoluciones.php");
    exit();
}

// Obtener ventas y productos
$ventas = $conn->query("SELECT id FROM ventas");
$productos = $conn->query("SELECT id, nombre FROM productos");
?>
<?php include '../includes/header.php'; ?>

<link rel="stylesheet" href="../css/estilos.css">

<h2>Registrar Devolución</h2>
<form method="POST">
    Venta ID:
    <select name="id_venta">
        <?php while ($v = $ventas->fetch_assoc()): ?>
        <option value="<?= $v['id'] ?>"><?= $v['id'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    Producto:
    <select name="id_producto">
        <?php while ($p = $productos->fetch_assoc()): ?>
        <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    Cantidad: <input type="number" name="cantidad" min="1" required><br><br>
    Motivo: <textarea name="motivo" required></textarea><br><br>

    <input type="submit" value="Registrar Devolución">
</form>