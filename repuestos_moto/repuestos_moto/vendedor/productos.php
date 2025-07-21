<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'vendedor') {
    header("Location: ../login/login.php");
    exit();
}

include '../config/config.php';
include '../includes/header.php';
include '../includes/funciones.php';
include 'verificar_sesion.php'; // Controla el rol vendedor

$resultado = $conn->query("SELECT * FROM productos");
?>
<link rel="stylesheet" href="../css/estilos.css">

<h2>Gestión de Productos</h2>
<a href="agregar_producto.php">+ Agregar nuevo producto</a>
<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($producto = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?= $producto['id'] ?></td>
            <td><?= $producto['nombre'] ?></td>
            <td><?= $producto['descripcion'] ?></td>
            <td>S/ <?= number_format($producto['precio'], 2) ?></td>
            <td><?= $producto['stock'] ?></td>
            <td>
                <a href="editar_producto.php?id=<?= $producto['id'] ?>">Editar</a> |
                <a href="eliminar_producto.php?id=<?= $producto['id'] ?>"
                    onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php include '../includes/footer.php'; ?>