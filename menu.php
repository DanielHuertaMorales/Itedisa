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

      <!-- Language Selector -->
      <div class="ml-4">
        <select id="langSelect" class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
          <option value="es">Español</option>
          <option value="en">English</option>
        </select>
      </div>

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
    <a href="index.php#contacto" class="block text-gray-700 hover:text-red-700">Contáctanos</a>
  </div>
</nav>