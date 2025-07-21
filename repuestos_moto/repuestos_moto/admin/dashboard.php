<?php
include '../config/config.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../login/login.php");
    exit();
}

// Total ventas del día
$hoy = date("Y-m-d");
$res = $conn->query("SELECT SUM(total) as total_dia FROM ventas WHERE DATE(fecha) = '$hoy'");
$total_dia = $res->fetch_assoc()['total_dia'] ?? 0;

// Total del mes
$mes = date("m");
$año = date("Y");
$res = $conn->query("SELECT SUM(total) as total_mes FROM ventas WHERE MONTH(fecha) = '$mes' AND YEAR(fecha) = '$año'");
$total_mes = $res->fetch_assoc()['total_mes'] ?? 0;

// Producto más vendido
$res = $conn->query("
    SELECT p.nombre, SUM(dv.cantidad) AS total_vendidos 
    FROM detalle_ventas dv 
    JOIN productos p ON dv.id_producto = p.id 
    GROUP BY dv.id_producto 
    ORDER BY total_vendidos DESC LIMIT 1
");
$producto_top = $res->fetch_assoc();

// Total acumulado
$res = $conn->query("SELECT SUM(total) as total_general FROM ventas");
$total_general = $res->fetch_assoc()['total_general'] ?? 0;
?>
<?php include '../admin/header_admin.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <script>
    window.onload = function() {
        if (localStorage.getItem("modo") === "oscuro") {
            document.body.classList.add("dark-mode");
        }
    };

    function toggleTheme() {
        document.body.classList.toggle("dark-mode");
        localStorage.setItem("modo", document.body.classList.contains("dark-mode") ? "oscuro" : "claro");
    }
    </script>
</head>

<body>
    <button class="toggle-theme" onclick="toggleTheme()">🌗 Tema</button>

    <div class="container">
        <h2>📊 Dashboard del Administrador</h2>
        <hr>

        <h3>Resumen de ventas</h3>
        <ul>
            <li>💰 Ventas hoy: <strong>S/ <?= number_format($total_dia, 2) ?></strong></li>
            <li>📅 Ventas este mes: <strong>S/ <?= number_format($total_mes, 2) ?></strong></li>
            <li>🔥 Producto más vendido:
                <strong>
                    <?= $producto_top['nombre'] ?? 'Ninguno' ?>
                    (<?= $producto_top['total_vendidos'] ?? 0 ?> uds)
                </strong>
            </li>
            <li>📈 Total acumulado: <strong>S/ <?= number_format($total_general, 2) ?></strong></li>
        </ul>

        <hr>
        <h3>📆 Reportes por fecha</h3>
        <form method="GET">
            <label for="desde">Desde:</label>
            <input type="date" name="desde" required>
            <label for="hasta">Hasta:</label>
            <input type="date" name="hasta" required>
            <input type="submit" value="Ver Reporte">
        </form>

        <?php
        if (isset($_GET['desde']) && isset($_GET['hasta'])) {
            $desde = $_GET['desde'];
            $hasta = $_GET['hasta'];

            $res = $conn->query("
                SELECT v.id, v.fecha, v.total, c.nombre AS cliente 
                FROM ventas v 
                JOIN clientes c ON v.id_cliente = c.id 
                WHERE DATE(v.fecha) BETWEEN '$desde' AND '$hasta'
            ");

            echo "<h4>📄 Ventas del $desde al $hasta</h4>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Cliente</th><th>Fecha</th><th>Total</th></tr>";
            while ($row = $res->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['cliente']}</td>
                        <td>{$row['fecha']}</td>
                        <td>S/ ".number_format($row['total'],2)."</td>
                      </tr>";
            }
            echo "</table>";
        }
        ?>

        <br>
    </div>
</body>

</html>