<?php
include "includes/db.php";
$categoria = $_GET['cat'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Productos - <?php echo htmlspecialchars($categoria); ?></title>
  <link rel="stylesheet" href="css/custom.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 pt-[120px]">

<?php include 'menu.php'; ?>

<main class="flex pt-24 px-6 pb-16 gap-8">>
  <!-- Barra lateral con filtros -->
  <aside class="w-1/4 bg-white rounded-2xl shadow-lg p-6 sticky top-28 self-start">
    <h2 class="text-xl font-semibold mb-4 text-blue-900 border-b pb-2">Filtrar por Marca</h2>
    <div id="filtros-marcas" class="space-y-3">
      <?php
      $stmt = $conn->prepare("SELECT id, nombre FROM marcas ORDER BY nombre");
      $stmt->execute();
      $result = $stmt->get_result();
      while ($marca = $result->fetch_assoc()) {
        echo "
          <label class='flex items-center gap-3 text-sm text-gray-800 cursor-pointer hover:text-blue-700 transition-all'>
            <input type='checkbox' class='marca-filter accent-blue-600 w-4 h-4' value='{$marca['id']}'>
            <span>{$marca['nombre']}</span>
          </label>
        ";
      }
      ?>
    </div>
  </aside>

  <!-- Sección de productos -->
  <section class="w-3/4">
    <h1 class="text-2xl font-bold mb-4">Productos: <?php echo htmlspecialchars($categoria); ?></h1>
    <div id="productos-lista" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
      <!-- Aquí se cargan los productos con AJAX -->
    </div>
    <div id="paginacion" class="mt-6 flex justify-center gap-2"></div>
  </section>
</>

<script>
const categoria = "<?php echo $categoria; ?>";

function cargarProductos(marcas = [], pagina = 1) {
  $.ajax({
    url: 'ajax_productos_categoria.php',
    method: 'POST',
    data: {
      categoria,
      marcas,
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

  $(document).on('change', '.marca-filter', function () {
    const marcasSeleccionadas = $('.marca-filter:checked').map(function () {
      return $(this).val();
    }).get();
    cargarProductos(marcasSeleccionadas);
  });

  $(document).on('click', '.paginacion-link', function (e) {
    e.preventDefault();
    const page = $(this).data('page');
    const marcasSeleccionadas = $('.marca-filter:checked').map(function () {
      return $(this).val();
    }).get();
    cargarProductos(marcasSeleccionadas, page);
  });
});
</script>

</body>
</html>
