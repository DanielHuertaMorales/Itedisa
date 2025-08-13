<?php 
include "includes/db.php";
$categoria = $_GET['cat'] ?? '';

// Obtener subcategorías de la categoría actual
$stmtSub = $conn->prepare("SELECT id, nombre FROM subcategorias WHERE categoria_id = (SELECT id FROM categorias WHERE nombre = ?) ORDER BY nombre");
$stmtSub->bind_param("s", $categoria);
$stmtSub->execute();
$resultSub = $stmtSub->get_result();
$subcategorias = [];
while ($row = $resultSub->fetch_assoc()) {
    $subcategorias[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Productos - <?php echo htmlspecialchars($categoria); ?></title>
  <link rel="stylesheet" href="css/custom.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 pt-[120px] flex flex-col min-h-screen">

  <?php include 'menu.php'; ?>

  <main class="flex pt-14 px-6 pb-16 gap-8 flex-grow">
    <!-- Barra lateral con filtros (solo escritorio) -->
    <aside class="hidden lg:block w-1/4 bg-white rounded-2xl shadow-lg p-6 sticky top-32 self-start">
      <h2 class="text-xl font-semibold mb-4 text-red-900 border-b pb-2">Filtrar por Marca</h2>
      <div id="filtros-marcas" class="space-y-3">
        <?php
        $stmt = $conn->prepare("SELECT id, nombre FROM marcas ORDER BY nombre");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($marca = $result->fetch_assoc()) {
          echo "
            <label class='flex items-center gap-3 text-sm text-gray-800 cursor-pointer hover:text-red-700 transition-all'>
              <input type='checkbox' class='marca-filter accent-red-600 w-4 h-4' value='{$marca['id']}'>
              <span>{$marca['nombre']}</span>
            </label>
          ";
        }
        ?>
      </div>
    </aside>

    <!-- Sección de productos -->
    <section class="w-full lg:w-3/4">
      <h1 class="text-2xl font-bold mb-4 flex items-center justify-between">
        <span>Productos: <?php echo htmlspecialchars($categoria); ?></span>
        
        <select id="selectSubcategoria" class="border border-gray-300 rounded px-2 py-1 text-sm ml-4">
          <option value="">Todas las subcategorías</option>
          <?php foreach ($subcategorias as $sub): ?>
            <option value="<?php echo $sub['id']; ?>"><?php echo htmlspecialchars($sub['nombre']); ?></option>
          <?php endforeach; ?>
        </select>
      </h1>
      <div id="productos-lista" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Aquí se cargan los productos con AJAX -->
      </div>
      <div id="paginacion" class="mt-6 flex justify-center gap-2"></div>
    </section>
  </main>

  <!-- Botón de filtros en móviles -->
  <div class="lg:hidden fixed bottom-6 right-6 z-50">
    <button id="openFilters" class="bg-red-600 text-white px-4 py-2 rounded-full shadow-lg">
      Filtros
    </button>
  </div>

  <!-- Off-canvas de filtros (móviles) -->
  <div id="mobileFilters" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div id="filtersPanel" class="bg-white w-64 h-full p-6 shadow-xl transform -translate-x-full transition-transform duration-300">
      <button id="closeFilters" class="mb-4 text-gray-700 hover:text-red-600">Cerrar ✕</button>
      <h2 class="text-xl font-semibold mb-4 text-red-900 border-b pb-2">Filtrar por Marca</h2>
      <div id="filtros-marcas-mobile" class="space-y-3">
        <?php
        // Reutilizamos los mismos filtros para móviles
        $stmt->execute();
        $result = $stmt->get_result();
        while ($marca = $result->fetch_assoc()) {
          echo "
            <label class='flex items-center gap-3 text-sm text-gray-800 cursor-pointer hover:text-red-700 transition-all'>
              <input type='checkbox' class='marca-filter accent-red-600 w-4 h-4' value='{$marca['id']}'>
              <span>{$marca['nombre']}</span>
            </label>
          ";
        }
        ?>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-gray-900 text-gray-300 py-10 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
      <div class="flex space-x-6">
        <a href="https://www.facebook.com/people/Itedisa-SA-de-CV/100090168609896/?sk=about" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="hover:text-blue-600 transition">
          <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54v-2.89h2.54V9.845c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.464h-1.26c-1.243 0-1.63.771-1.63 1.562v1.875h2.773l-.443 2.89h-2.33v6.987C18.343 21.128 22 16.99 22 12z"/></svg>
        </a>
        <a href="https://mx.linkedin.com/company/itedisasadecv" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="hover:text-blue-400 transition">
          <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.76 0-5 2.24-5 5v14c0 2.76 2.24 5 5 5h14c2.762 0 5-2.24 5-5v-14c0-2.76-2.238-5-5-5zm-11.75 20h-3v-11h3v11zm-1.5-12.22c-.967 0-1.75-.783-1.75-1.75s.783-1.75 1.75-1.75 1.75.783 1.75 1.75-.783 1.75-1.75 1.75zm13.25 12.22h-3v-5.5c0-1.38-.02-3.15-1.92-3.15-1.92 0-2.22 1.5-2.22 3.05v5.6h-3v-11h2.88v1.5h.04c.4-.75 1.38-1.54 2.84-1.54 3.04 0 3.6 2 3.6 4.6v6.44z"/></svg>
        </a>
      </div>
      <div class="text-center md:text-left">
        <p class="font-semibold">Contacto:</p>
        <a href="mailto:contacto@itedisa.com" class="hover:text-red-600 transition">contacto@itedisa.com</a>
      </div>
      <div class="text-center text-sm text-gray-500">
        &copy; <?php echo date('Y'); ?> ITEDISA. Hecho con <span class="text-red-600">❤️</span>.
      </div>
    </div>
  </footer>

  <script>
    const categoria = "<?php echo addslashes($categoria); ?>";

    function cargarProductos(marcas = [], subcategoria = '', pagina = 1) {
      $.ajax({
        url: 'ajax_productos_categoria.php',
        method: 'POST',
        data: {
          categoria,
          marcas,
          subcategoria,
          pagina
        },
        success: function (res) {
          const data = JSON.parse(res);
          $('#productos-lista').html(data.html);
          $('#paginacion').html(data.paginacion);
        }
      });
    }

    $(document).ready(function () {
      cargarProductos();

      $(document).on('change', '.marca-filter, #selectSubcategoria', function () {
        const marcasSeleccionadas = $('.marca-filter:checked').map(function () {
          return $(this).val();
        }).get();

        const subcategoriaSeleccionada = $('#selectSubcategoria').val();

        cargarProductos(marcasSeleccionadas, subcategoriaSeleccionada);
      });

      $(document).on('click', '.paginacion-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        const marcasSeleccionadas = $('.marca-filter:checked').map(function () {
          return $(this).val();
        }).get();

        const subcategoriaSeleccionada = $('#selectSubcategoria').val();

        cargarProductos(marcasSeleccionadas, subcategoriaSeleccionada, page);
      });

      // Off-canvas de filtros
      const openBtn = document.getElementById("openFilters");
      const closeBtn = document.getElementById("closeFilters");
      const overlay = document.getElementById("mobileFilters");
      const panel = document.getElementById("filtersPanel");

      openBtn.addEventListener("click", () => {
        overlay.classList.remove("hidden");
        setTimeout(() => panel.classList.remove("-translate-x-full"), 10);
      });

      closeBtn.addEventListener("click", () => {
        panel.classList.add("-translate-x-full");
        setTimeout(() => overlay.classList.add("hidden"), 300);
      });

      overlay.addEventListener("click", (e) => {
        if (e.target === overlay) {
          panel.classList.add("-translate-x-full");
          setTimeout(() => overlay.classList.add("hidden"), 300);
        }
      });
    });
  </script>
</body>
</html>
