<?php
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

// Opcional: verifica si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once(__DIR__ . '/../config.php');
?>

<!-- menu.php -->

<nav class="bg-white shadow-md fixed top-0 w-full z-50" style="height:120px;">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
    <div class="flex justify-between h-full items-center">
      <!-- Logo -->
      <div class="flex-shrink-0 flex items-center">
        <img src="<?php echo $base_url; ?>assets/Logotipo.png" alt="Logo ITEDISA" class="h-24" />
      </div>

      <!-- Menu Items -->
      <div class="hidden md:flex space-x-6">
        <a href="agregar_productos_manual.php" class="text-gray-700 hover:text-red-700 transition font-semibold text-lg px-4 py-2">
          Agregar Productos
        </a>
        <a href="agregar_productos_manual.php" class="text-gray-700 hover:text-red-700 transition font-semibold text-lg px-4 py-2">
          Productos
        </a>
        <a href="categorias.php" class="text-gray-700 hover:text-red-700 transition font-semibold text-lg px-4 py-2">
          Categorías
        </a>
        <a href="subcategorias.php" class="text-gray-700 hover:text-red-700 transition font-semibold text-lg px-4 py-2">
          Subcategorías
        </a>
        <a href="logout.php" class="text-gray-700 hover:text-red-700 transition font-semibold text-lg px-4 py-2">
          Salir
        </a>
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
    <a href="subir_pdf.php#nosotros" class="block text-gray-700 hover:text-red-700">Nosotros</a>
    <a href="logout.php" class="block text-gray-700 hover:text-red-700">Productos</a>
  </div>
</nav>