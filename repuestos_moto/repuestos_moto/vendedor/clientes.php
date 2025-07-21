<?php
include '../config/config.php';
include 'verificar_sesion.php';

// Insertar nuevo cliente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $dni = $_POST['dni'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    $sql = "INSERT INTO clientes (nombre, dni, direccion, telefono) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $dni, $direccion, $telefono);
    $stmt->execute();
    header("Location: clientes.php");
    exit();
}

// Mostrar clientes
$resultado = $conn->query("SELECT * FROM clientes");
?>
<?php include '../includes/header.php'; ?>

<link rel="stylesheet" href="../css/estilos.css">

<h2>Gestión de Clientes</h2>
<form method="POST">
    Nombre: <input type="text" name="nombre" required>
    DNI: <input type="text" name="dni" required>
    Dirección: <input type="text" name="direccion" required>
    Teléfono: <input type="text" name="telefono" required>
    <input type="submit" value="Agregar Cliente">
</form>

<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>DNI</th>
            <th>Dirección</th>
            <th>Teléfono</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($c = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= $c['nombre'] ?></td>
            <td><?= $c['dni'] ?></td>
            <td><?= $c['direccion'] ?></td>
            <td><?= $c['telefono'] ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>