<?php
include '../config/config.php';
include 'verificar_sesion.php';

// Obtener productos y clientes
$productos = $conn->query("SELECT * FROM productos WHERE stock > 0");
$clientes = $conn->query("SELECT * FROM clientes");
?>
<?php include '../includes/header.php'; ?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Venta</title>
    <link rel="stylesheet" href="../css/estilos.css">

    <script>
    window.onload = function() {
        if (localStorage.getItem("modo") === "oscuro") {
            document.body.classList.add("dark-mode");
        }
    };

    function toggleTheme() {
        document.body.classList.toggle("dark-mode");
        if (document.body.classList.contains("dark-mode")) {
            localStorage.setItem("modo", "oscuro");
        } else {
            localStorage.setItem("modo", "claro");
        }
    }
    </script>
</head>

<body>
    <button class="toggle-theme" onclick="toggleTheme()">🌗 Tema</button>
    <link rel="header" href="../includes/header.php">
    <div class="container">
        <h2>🧾 Registrar Venta</h2>

        <form action="procesar_venta.php" method="POST">
            <label for="cliente_id">Cliente:</label>
            <select name="cliente_id" id="cliente_id" required>
                <option value="">Seleccione un cliente</option>
                <?php while ($c = $clientes->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
                <?php endwhile; ?>
            </select>

            <h3>📦 Productos:</h3>
            <div id="productos-container">
                <div class="producto-item" style="margin-bottom: 1rem;">
                    <select name="producto_id[]" required>
                        <option value="">Seleccione producto</option>
                        <?php
                        $productos->data_seek(0); // reiniciar puntero
                        while ($p = $productos->fetch_assoc()): ?>
                        <option value="<?= $p['id'] ?>">
                            <?= $p['nombre'] ?> - S/<?= $p['precio'] ?> (Stock: <?= $p['stock'] ?>)
                        </option>
                        <?php endwhile; ?>
                    </select>
                    <label for="cantidad[]" style="display:block; margin-top: 0.5rem;">Cantidad:</label>
                    <input type="number" name="cantidad[]" min="1" required>
                    <button type="button" onclick="eliminarProducto(this)" style="margin-top: 0.5rem;">❌
                        Eliminar</button>
                </div>
            </div>

            <button type="button" onclick="agregarProducto()">➕ Agregar otro producto</button><br><br>
            <input type="submit" value="Registrar Venta">
        </form>
    </div>

    <script>
    function agregarProducto() {
        const container = document.getElementById('productos-container');
        const clone = container.firstElementChild.cloneNode(true);
        container.appendChild(clone);
    }

    function eliminarProducto(boton) {
        const item = boton.parentElement;
        const container = document.getElementById('productos-container');
        if (container.children.length > 1) {
            item.remove();
        }
    }
    </script>
</body>

</html>