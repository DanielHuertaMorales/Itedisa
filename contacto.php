<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Itedisa - Inicio</title>
  <link rel="stylesheet" href="css/custom.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

  <?php include 'menu.php'; ?>

  <!-- Contenido principal con margen para el navbar -->
  <main class="pt-[120px] px-4 pb-10">
    <section class="relative w-full max-h-[700px] overflow-hidden">
      <div id="carousel" class="relative w-full h-[600px]">
        <img src="assets/Carrusel/Carrusel1.png" alt="Imagen 1" class="absolute inset-0 w-full h-full object-cover opacity-100 transition-opacity duration-1000" />
        <img src="assets/Carrusel/Carrusel2.png" alt="Imagen 2" class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000" />
        <img src="assets/Carrusel/Carrusel3.png" alt="Imagen 3" class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000" />
      </div>

      <!-- Flechas -->
      <button id="prevBtn" class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-black bg-opacity-30 text-white rounded-full p-3 hover:bg-opacity-50 transition z-10">
        &#10094;
      </button>
      <button id="nextBtn" class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-black bg-opacity-30 text-white rounded-full p-3 hover:bg-opacity-50 transition z-10">
        &#10095;
      </button>
    </section>

    <h2 class="text-xl font-semibold mb-4">Categorías de productos</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <a href="productos.php?cat=multimetros" class="bg-white shadow p-4 rounded hover:bg-gray-50 transition">Multímetros</a>
      <a href="productos.php?cat=osciloscopios" class="bg-white shadow p-4 rounded hover:bg-gray-50 transition">Osciloscopios</a>
    </div>

    <h2 class="text-xl font-semibold mt-8 mb-4">Marcas</h2>
    <div class="grid grid-cols-2 gap-4">
      <a href="marca.php?marca=fluke" class="bg-yellow-400 text-black p-4 rounded hover:bg-yellow-300">Fluke</a>
      <a href="marca.php?marca=tektronix" class="bg-blue-400 text-white p-4 rounded hover:bg-blue-300">Tektronix</a>
    </div>
  </main>

  <script src="js/main.js"></script>

</body>
</html>

