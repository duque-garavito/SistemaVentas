<?php
include '../config/config.php';
include 'verificar_sesion.php';
include '../includes/header.php';

// Últimas 5 ventas
$ventas = $conn->query("
SELECT v.id, v.total, v.fecha, c.nombre AS cliente
FROM ventas v
INNER JOIN clientes c ON v.id_cliente = c.id
ORDER BY v.fecha DESC LIMIT 5
");

// Productos con bajo stock
$stock_bajo = $conn->query("SELECT * FROM productos WHERE stock <= 5"); ?>
<link rel="stylesheet" href="../css/estilos.css">
<link rel="header" href="../includes/header.php">
<h2>Bienvenido, <?= $_SESSION['usuario'] ?> 👋</h2>
<hr>

<h3>🔁 Últimas Ventas</h3>
<table border="1" cellpadding="6">
    <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Total</th>
        <th>Fecha</th>
    </tr>
    <?php while ($v = $ventas->fetch_assoc()): ?>
        <tr>
            <td><?= $v['id'] ?></td>
            <td><?= $v['cliente'] ?></td>
            <td>S/ <?= number_format($v['total'], 2) ?></td>
            <td><?= $v['fecha'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<h3>⚠️ Productos con bajo stock</h3>
<table border="1" cellpadding="6">
    <tr>
        <th>Nombre</th>
        <th>Stock</th>
    </tr>
    <?php while ($p = $stock_bajo->fetch_assoc()): ?>
        <tr>
            <td><?= $p['nombre'] ?></td>
            <td><?= $p['stock'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<hr>