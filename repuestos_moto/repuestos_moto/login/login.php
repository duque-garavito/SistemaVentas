<?php
session_start();
include('../config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    $query = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $user = $resultado->fetch_assoc();
        if (password_verify($clave, $user['clave'])) {
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['rol'] = $user['rol'];

            if ($user['rol'] == 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../vendedor/index.php");
            }
            exit();
        } else {
            $error = "Clave incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <script>
    // Activar modo guardado en localStorage
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

    <div class="container" style="max-width: 400px; margin-top: 5rem;">
        <h2 style="text-align: center;">🔒 Iniciar Sesión</h2>
        <?php if (isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
        <form method="POST" action="">
            <label for="usuario">Usuario</label>
            <input type="text" name="usuario" id="usuario" required>

            <label for="clave">Clave</label>
            <input type="password" name="clave" id="clave" required>

            <input type="submit" value="Ingresar">
        </form>
    </div>
</body>

</html>