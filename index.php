<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Itedisa - Inicio</title>
  <link rel="stylesheet" href="css/custom.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-fixed bg-cover bg-center bg-no-repeat" style="background-image: url('assets/fondo.png');">

  <?php include 'menu.php'; ?>

  <!-- Contenido principal con margen para el navbar -->
  <main class="pt-[120px] px-4 pb-10">
    <section class="relative w-full max-h-[900px] overflow-hidden">
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

    <section class="max-w-[1600px] mx-auto px-10 py-20">
      <h2 class="text-5xl font-bold text-center text-red-600 mb-16">¿Por qué elegirnos?</h2>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden hover:scale-105 transition-transform duration-300">
          <img src="assets/Quality.png" alt="Calidad Garantizada" class="w-full h-[300px] object-cover">
          <div class="p-10">
            <h3 class="text-4xl font-bold text-gray-800 mb-4">Calidad Garantizada</h3>
            <p class="text-xl text-gray-700">Nuestros productos cumplen con los más altos estándares, asegurando eficiencia y durabilidad en cada proyecto.</p>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden hover:scale-105 transition-transform duration-300">
          <img src="assets/Support.png" alt="Soporte Especializado" class="w-full h-[300px] object-cover">
          <div class="p-10">
            <h3 class="text-4xl font-bold text-gray-800 mb-4">Soporte Especializado</h3>
            <p class="text-xl text-gray-700">Contamos con un equipo experto dispuesto a ayudarte en cada etapa del proceso, desde la selección hasta la postventa.</p>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden hover:scale-105 transition-transform duration-300">
          <img src="assets/fastShip.png" alt="Entrega Rápida" class="w-full h-[300px] object-cover">
          <div class="p-10">
            <h3 class="text-4xl font-bold text-gray-800 mb-4">Entrega Rápida</h3>
            <p class="text-xl text-gray-700">Sabemos que tu tiempo es valioso, por eso garantizamos envíos eficientes y sin demoras innecesarias.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="w-full h-[300px] bg-[url('assets/marcas.png')] bg-cover bg-center bg-no-repeat md:bg-fixed relative group">
      <!-- Fondo negro más oscuro para mejor contraste -->
      <div class="absolute inset-0 bg-black/60 transition duration-500 group-hover:backdrop-blur-sm group-hover:bg-black/70"></div>

      <div class="relative z-10 h-full flex items-center justify-center text-center px-6">
        <div>
          <h2 class="text-white text-5xl md:text-6xl font-extrabold mb-6 leading-tight">
            Tenemos marcas destacadas en el sector industrial
          </h2>
          <p class="text-white text-xl md:text-2xl max-w-5xl mx-auto leading-relaxed">
            Colaboramos con marcas de prestigio internacional que destacan por su innovación, eficiencia y compromiso con la excelencia operativa en la industria energética, petrolera y más.
          </p>
        </div>
      </div>
    </section>




    <section class="max-w-[1600px] mx-auto px-10 py-20">
      <h2 class="text-5xl font-bold text-center text-red-600 mb-16">Productos más vendidos</h2>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
        <!-- Producto 1 -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden hover:scale-105 transition-transform duration-300">
          <img src="assets/img/productos/Bombax100.jpg" alt="Producto 1" class="w-full h-[300px] object-cover">
          <div class="p-8">
            <span class="inline-block bg-red-600 text-white text-sm font-semibold px-4 py-1 rounded-full mb-3">Marca: XBrand</span>
            <h3 class="text-3xl font-semibold text-gray-800 mb-3">Bomba Centrífuga X100</h3>
            <p class="text-lg text-gray-700 mb-6">Alta eficiencia y durabilidad para aplicaciones industriales exigentes.</p>
            <button class="bg-red-600 text-white px-6 py-2 rounded-full hover:bg-red-700 transition-colors">Ver más</button>
          </div>
        </div>

        <!-- Producto 2 -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden hover:scale-105 transition-transform duration-300">
          <img src="assets/img/productos/valvula.jpg" alt="Producto 2" class="w-full h-[300px] object-cover">
          <div class="p-8">
            <span class="inline-block bg-red-600 text-white text-sm font-semibold px-4 py-1 rounded-full mb-3">Marca: FlowTech</span>
            <h3 class="text-3xl font-semibold text-gray-800 mb-3">Válvula de Control V300</h3>
            <p class="text-lg text-gray-700 mb-6">Precisión y confiabilidad para controlar el flujo en tus procesos.</p>
            <button class="bg-red-600 text-white px-6 py-2 rounded-full hover:bg-red-700 transition-colors">Ver más</button>
          </div>
        </div>

        <!-- Producto 3 -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden hover:scale-105 transition-transform duration-300">
          <img src="assets/img/productos/filtro.jpg" alt="Producto 3" class="w-full h-[300px] object-cover">
          <div class="p-8">
            <span class="inline-block bg-red-600 text-white text-sm font-semibold px-4 py-1 rounded-full mb-3">Marca: CleanPro</span>
            <h3 class="text-3xl font-semibold text-gray-800 mb-3">Filtro Industrial FX200</h3>
            <p class="text-lg text-gray-700 mb-6">Rendimiento superior para mantener tus sistemas limpios y eficientes.</p>
            <button class="bg-red-600 text-white px-6 py-2 rounded-full hover:bg-red-700 transition-colors">Ver más</button>
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

<footer class="bg-gray-900 text-gray-300 py-8">
  <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
    <!-- Redes sociales -->
    <div class="flex space-x-6">
      <a href="https://www.facebook.com/people/Itedisa-SA-de-CV/100090168609896/?sk=about" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="hover:text-blue-600 transition">
        <!-- Icono Facebook -->
        <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54v-2.89h2.54V9.845c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.464h-1.26c-1.243 0-1.63.771-1.63 1.562v1.875h2.773l-.443 2.89h-2.33v6.987C18.343 21.128 22 16.99 22 12z"/>
        </svg>
      </a>
      <a href="https://mx.linkedin.com/company/itedisasadecv" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="hover:text-blue-400 transition">
        <!-- Icono LinkedIn -->
        <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M19 0h-14c-2.76 0-5 2.24-5 5v14c0 2.76 2.24 5 5 5h14c2.762 0 5-2.24 5-5v-14c0-2.76-2.238-5-5-5zm-11.75 20h-3v-11h3v11zm-1.5-12.22c-.967 0-1.75-.783-1.75-1.75s.783-1.75 1.75-1.75 1.75.783 1.75 1.75-.783 1.75-1.75 1.75zm13.25 12.22h-3v-5.5c0-1.38-.02-3.15-1.92-3.15-1.92 0-2.22 1.5-2.22 3.05v5.6h-3v-11h2.88v1.5h.04c.4-.75 1.38-1.54 2.84-1.54 3.04 0 3.6 2 3.6 4.6v6.44z"/>
        </svg>
      </a>
    </div>

    <!-- Correo -->
    <div class="text-center md:text-left">
      <p class="font-semibold">Contacto:</p>
      <a href="mailto:contacto@itedisa.com" class="hover:text-red-600 transition">contacto@itedisa.com</a>
    </div>

    <!-- Copyright -->
    <div class="text-center text-sm text-gray-500">
      &copy; <?php echo date('Y'); ?> ITEDISA. Hecho con <span class="text-red-600">❤️</span>.
    </div>
  </div>
</footer>


  <script src="js/main.js"></script>

</body>
</html>

