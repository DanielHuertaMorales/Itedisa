<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Itedisa - Inicio</title>
  <link rel="stylesheet" href="css/custom.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-fixed bg-cover bg-center bg-no-repeat" style="background-image: url('assets/FondoBlanco.png');">

  <?php include 'menu.php'; ?>

  <!-- Contenido principal con margen para el navbar -->
  <main class="pt-[120px] px-4 pb-10">
    <section class="relative w-full max-h-[700px] overflow-hidden">
      <!-- Contenedor sólo para las imágenes -->
      <div id="carousel-images" class="relative w-full h-[600px]">
        <img src="assets/Carrusel/Carrusel1.png" alt="Imagen 1" class="absolute inset-0 w-full h-full object-cover opacity-100 transition-opacity duration-1000" />
        <img src="assets/Carrusel/Carrusel2.png" alt="Imagen 2" class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000" />
        <img src="assets/Carrusel/Carrusel3.png" alt="Imagen 3" class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000" />
      </div>

      <!-- Texto fijo sobre el carrusel -->
      <div class="absolute inset-0 flex justify-center items-center pointer-events-none px-4">
        <div class="bg-black bg-opacity-30 p-8 rounded pointer-events-auto max-w-4xl text-center">
          <!-- Estrellas de calidad -->
          <div class="flex justify-center mb-4 space-x-1">
            <!-- 5 estrellas amarillas -->
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.974c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.539-1.118l1.286-3.974a1 1 0 00-.364-1.118L2.045 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z"/></svg>
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.974c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.539-1.118l1.286-3.974a1 1 0 00-.364-1.118L2.045 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z"/></svg>
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.974c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.539-1.118l1.286-3.974a1 1 0 00-.364-1.118L2.045 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z"/></svg>
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.974c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.539-1.118l1.286-3.974a1 1 0 00-.364-1.118L2.045 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z"/></svg>
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.974c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.539-1.118l1.286-3.974a1 1 0 00-.364-1.118L2.045 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z"/></svg>
          </div>

          <h1 class="text-6xl md:text-7xl font-bold mb-6 drop-shadow-lg text-white">Productos industriales de calidad</h1>
          <p class="text-2xl md:text-3xl mb-8 drop-shadow-md text-white">Ofrecemos bombas, válvulas y otros productos de marcas reconocidas en la industria. ¡Descubre nuestro catálogo ahora!</p>
          <a href="contacto.php" class="bg-red-700 hover:bg-red-800 text-white font-semibold py-4 px-8 rounded shadow-lg transition inline-block">
            Contáctanos
          </a>
        </div>
      </div>

      <!-- Flechas para controlar el carrusel -->
      <button id="prevBtn" class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-black bg-opacity-30 text-white rounded-full p-3 hover:bg-opacity-50 transition z-10">
        &#10094;
      </button>
      <button id="nextBtn" class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-black bg-opacity-30 text-white rounded-full p-3 hover:bg-opacity-50 transition z-10">
        &#10095;
      </button>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-16 flex flex-col md:flex-row items-center gap-12">
      <!-- Texto a la izquierda -->
      <div class="md:w-1/2">
        <h1 class="text-6xl font-extrabold mb-6 leading-tight text-red-600">Conócenos y descubre calidad</h1>
        <p class="text-xl text-gray-800 max-w-lg">
          En Itedisa, somos proveedores de productos industriales como bombas y válvulas, ofreciendo un amplio catálogo de marcas líderes en el sector. Tu confianza es nuestra prioridad.
        </p>
        <p class="text-xl text-gray-800 max-w-lg">
          Generar confianza en cada contacto con el cliente al mostrar cómo nuestra experiencia en productos para la industria, la atención a los más altos estándares de calidad, y la diversidad de nuestras ofertas contribuyen al éxito de nuestros clientes. Pueden confiar plenamente en que cada miembro de nuestro equipo está constantemente comprometido en alcanzar estos rigurosos estándares.
        </p>
      </div>

      <!-- Imagen a la derecha con animación y tag de marca -->
      <div class="md:w-1/2 flex justify-center">
        <div class="relative w-[700px] h-[450px] rounded-xl overflow-hidden shadow-2xl group">
          <!-- Imagen -->
          <img src="assets/indexIndustry.png" alt="Conócenos ITEDISA"
              class="w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105" />
          
          <!-- Brand dentro del div, abajo -->
          <div class="absolute bottom-0 left-0 w-full bg-red-600 bg-opacity-90 text-white text-center py-3 text-lg font-semibold">
            ITEDISA SA DE CV
          </div>
        </div>
      </div>
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

