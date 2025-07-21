<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>

<body>
    <nav class="navbar">
        <a href="productos.php" class="menu">📦 Productos</a> |
        <a href="ventas.php" class="menu">🛒 Ventas</a> |
        <a href="clientes.php" class="menu">👥 Clientes</a> |
        <a href="devoluciones.php" class="menu">↩️ Devoluciones</a> |
        <a href="perfil.php" class="menu">👤 Perfil</a> |
        <a href="../login/logout.php" class="menu">🔓 Cerrar sesión</a>
    </nav>
</body>
<script>
function toggleMenu() {
    const menu = document.getElementById("loginMenu");
    menu.classList.toggle("show-menu");
}

window.onclick = function(event) {
    const menu = document.getElementById("loginMenu");
    const button = document.querySelector('.user-button');

    if (menu && !button.contains(event.target) && !menu.contains(event.target)) {
        menu.classList.remove("show-menu");
    }
};

window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    header.classList.toggle('scrolled', window.scrollY > 50);
});
</script>

</html>