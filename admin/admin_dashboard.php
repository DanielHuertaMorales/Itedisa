<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Admin - ITEDISA</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    #particles-js {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        z-index: -1;
    }
</style>
</head>
<body class="flex items-center justify-center h-screen text-white">

<!-- Partículas -->
<div id="particles-js"></div>

<!-- Contenido -->
<div class="bg-white bg-opacity-10 backdrop-blur-md p-10 rounded-2xl shadow-2xl text-center animate__animated animate__fadeIn">
    <h1 class="text-4xl font-bold mb-4 text-red-500 drop-shadow-lg">
        ¡Bienvenido, <?= htmlspecialchars($username) ?>!
    </h1>
    <p class="mb-8 text-red-600">Panel de administración de ITEDISA</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="agregar_productos_manual.php" class="block bg-red-600 hover:bg-red-700 py-4 px-6 rounded-xl transition-all duration-300 text-lg font-semibold shadow-lg">📤 Subir Productos</a>
        <a href="productos.php" class="block bg-red-600 hover:bg-red-700 py-4 px-6 rounded-xl transition-all duration-300 text-lg font-semibold shadow-lg">📦 Productos</a>
        <a href="categorias.php" class="block bg-red-600 hover:bg-red-700 py-4 px-6 rounded-xl transition-all duration-300 text-lg font-semibold shadow-lg">📂 Categorías</a>
        <a href="subcategorias.php" class="block bg-red-600 hover:bg-red-700 py-4 px-6 rounded-xl transition-all duration-300 text-lg font-semibold shadow-lg">📑 Subcategorías</a>
        <a href="logout.php" class="block bg-gray-800 hover:bg-gray-900 py-4 px-6 rounded-xl transition-all duration-300 text-lg font-semibold shadow-lg">🚪 Salir</a>
    </div>
</div>

<!-- Particles.js -->
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
<script>
particlesJS("particles-js", {
    "particles": {
        "number": { "value": 80, "density": { "enable": true, "value_area": 800 } },
        "color": { "value": "#ff0000" },
        "shape": { "type": "circle" },
        "opacity": { "value": 0.5 },
        "size": { "value": 3 },
        "line_linked": { "enable": true, "distance": 150, "color": "#ff0000", "opacity": 0.4, "width": 1 },
        "move": { "enable": true, "speed": 3 }
    },
    "interactivity": {
        "events": {
            "onhover": { "enable": true, "mode": "repulse" },
            "onclick": { "enable": true, "mode": "push" }
        },
        "modes": {
            "repulse": { "distance": 100, "duration": 0.4 }
        }
    },
    "retina_detect": true
});
</script>
</body>
</html>
