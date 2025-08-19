<?php
    include_once 'config.php'; // asegúrate que esta ruta sea correcta según la ubicación
?>
<!-- menu.php -->

<nav class="bg-white shadow-md fixed top-0 w-full z-50" style="height:120px;">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
    <div class="flex justify-between h-full items-center">
      <!-- Logo -->
      <div class="flex-shrink-0 flex items-center">
        <img src="<?php echo $base_url; ?>assets/Logotipo.png" alt="Logo ITEDISA" class="h-20" />
      </div>

      <!-- Menu Items -->
      <div class="hidden md:flex space-x-6">
        <a href="index.php" class="text-gray-700 hover:text-red-700 transition font-semibold text-xl px-4 py-2">Nosotros</a>
        <a href="productos.php" class="text-gray-700 hover:text-red-700 transition font-semibold text-xl px-4 py-2">Productos</a>
        <a href="marca.php" class="text-gray-700 hover:text-red-700 transition font-semibold text-xl px-4 py-2">Marcas</a>
        <a href="contacto.php" class="text-gray-700 hover:text-red-700 transition font-semibold text-xl px-4 py-2">Contáctanos</a>
      </div>

      <!-- Language Selector + Carrito -->
      <!-- <div class="flex items-center space-x-4">
        < Selector de idioma -->
      
      <a href="cotizar.php" class="relative group" id="carritoIcono">
        <!-- Bolsa de compras minimalista -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-700 group-hover:text-red-700 transition-transform transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 10-8 0v4M5 11h14l1 10H4L5 11z"/>
        </svg>
        <!-- Contador de productos -->
        <span id="cartCount" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
          0
        </span>
      </a>
      
      <!-- Mobile menu button -->
      <div class="md:hidden">
        <button id="menuBtn" class="text-gray-700 focus:outline-none">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div id="mobileMenu" class="hidden md:hidden px-4 pt-2 pb-4 space-y-1 bg-white shadow">
    <a href="index.php#nosotros" class="block text-gray-700 hover:text-red-700">Nosotros</a>
    <a href="productos.php" class="block text-gray-700 hover:text-red-700">Productos</a>
    <a href="marca.php" class="block text-gray-700 hover:text-red-700">Marcas</a>
    <a href="contacto.php" class="block text-gray-700 hover:text-red-700">Contáctanos</a>
    <a href="cotizar.php" class="block text-gray-700 hover:text-red-700">🛒 Cotización</a>
  </div>

<script>
  window.addEventListener('load', function () {
    const menuBtn = document.getElementById('menuBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    if (menuBtn && mobileMenu) {
      menuBtn.addEventListener('click', function () {
        mobileMenu.classList.toggle('hidden');
      });
    }
  });
</script>

</nav>