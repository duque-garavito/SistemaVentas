<?php
include '../config/config.php';
include 'verificar_sesion.php';

$cliente_id = $_POST['cliente_id'];
$producto_ids = $_POST['producto_id'];
$cantidades = $_POST['cantidad'];
$usuario_id = $_SESSION['usuario_id']; // este debe estar en la sesión al hacer login

$total = 0;

// Calcular total
for ($i = 0; $i < count($producto_ids); $i++) {
    $stmt = $conn->prepare("SELECT precio FROM productos WHERE id = ?");
    $stmt->bind_param("i", $producto_ids[$i]);
    $stmt->execute();
    $result = $stmt->get_result();
    $precio = $result->fetch_assoc()['precio'];
    $total += $precio * $cantidades[$i];
}

// Insertar venta
$stmt = $conn->prepare("INSERT INTO ventas (id_cliente, id_usuario, total) VALUES (?, ?, ?)");
$stmt->bind_param("iid", $cliente_id, $usuario_id, $total);
$stmt->execute();
$venta_id = $stmt->insert_id;

// Insertar detalle y actualizar stock
for ($i = 0; $i < count($producto_ids); $i++) {
    $producto_id = $producto_ids[$i];
    $cantidad = $cantidades[$i];

    // Obtener precio actual
    $stmt = $conn->prepare("SELECT precio, stock FROM productos WHERE id = ?");
    $stmt->bind_param("i", $producto_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $precio = $row['precio'];
    $stock = $row['stock'];

    if ($cantidad > $stock) {
        echo "Error: stock insuficiente para el producto ID $producto_id";
        exit();
    }

    // Insertar detalle
    $stmt = $conn->prepare("INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $venta_id, $producto_id, $cantidad, $precio);
    $stmt->execute();

    // Actualizar stock
    $nuevo_stock = $stock - $cantidad;
    $stmt = $conn->prepare("UPDATE productos SET stock = ? WHERE id = ?");
    $stmt->bind_param("ii", $nuevo_stock, $producto_id);
    $stmt->execute();
}

header("Location: ventas.php?success=1");