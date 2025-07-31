<?php
include "includes/db.php";

$marca = isset($_GET['marca']) ? $conn->real_escape_string($_GET['marca']) : '';
$categoriaFiltro = isset($_GET['categoria']) ? $conn->real_escape_string($_GET['categoria']) : '';

if (empty($marca)) {
  echo "Marca no especificada.";
  exit;
}

// Obtener categorías disponibles para la marca
$sqlCategorias = "SELECT DISTINCT c.nombre 
                  FROM productos p
                  JOIN marcas m ON p.id_marca = m.id
                  JOIN categorias c ON p.id_categoria = c.id
                  WHERE m.nombre = '$marca'";
$resultCategorias = $conn->query($sqlCategorias);

// Obtener productos según filtro
$sql = "SELECT p.nombre, p.imagen, c.nombre AS categoria 
        FROM productos p
        JOIN marcas m ON p.id_marca = m.id
        JOIN categorias c ON p.id_categoria = c.id
        WHERE m.nombre = '$marca'";

if (!empty($categoriaFiltro)) {
  $sql .= " AND c.nombre = '$categoriaFiltro'";
}

$result = $conn->query($sql);

if (!$result) {
  die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Productos de <?php echo htmlspecialchars($marca); ?> | ITEDISA</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 pt-[120px] min-h-screen flex flex-col">
  <?php include 'menu.php'; ?>

  <main class="flex-grow max-w-7xl mx-auto px-6 pb-16">
    <h1 class="text-3xl font-bold text-center mb-10">
      Productos de <?php echo htmlspecialchars($marca); ?>
    </h1>

    <div class="flex gap-8">
      <!-- Filtro lateral -->
      <aside class="w-64 hidden md:block">
        <h2 class="text-xl font-semibold mb-4">Categorías</h2>
        <ul class="space-y-2">
          <li>
            <a href="?marca=<?php echo urlencode($marca); ?>" class="block px-3 py-2 rounded hover:bg-red-500 hover:text-white transition <?php if (empty($categoriaFiltro)) echo 'bg-red-500 text-white'; ?>">
              Todas
            </a>
          </li>
          <?php if ($resultCategorias && $resultCategorias->num_rows > 0): ?>
            <?php while($cat = $resultCategorias->fetch_assoc()): ?>
              <li>
                <a href="?marca=<?php echo urlencode($marca); ?>&categoria=<?php echo urlencode($cat['nombre']); ?>"
                   class="block px-3 py-2 rounded hover:bg-red-500 hover:text-white transition <?php if ($categoriaFiltro === $cat['nombre']) echo 'bg-red-500 text-white'; ?>">
                  <?php echo htmlspecialchars($cat['nombre']); ?>
                </a>
              </li>
            <?php endwhile; ?>
          <?php endif; ?>
        </ul>
      </aside>

      <!-- Productos -->
      <section class="flex-1">
        <?php if ($result && $result->num_rows > 0): ?>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php while($producto = $result->fetch_assoc()): ?>
              <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                <!-- Imagen sin recorte -->
                <div class="bg-gray-100 flex items-center justify-center aspect-[4/3] overflow-hidden">
                  <img src="assets/img/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                       alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                       class="max-h-full max-w-full object-contain transition duration-300 group-hover:scale-105" />
                </div>

                <!-- Contenido -->
                <div class="p-4 space-y-2 text-center">
                  <h2 class="text-lg font-bold text-gray-800">
                    <?php echo htmlspecialchars($producto['nombre']); ?>
                  </h2>
                  <span class="inline-block bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded">
                    <?php echo htmlspecialchars($producto['categoria']); ?>
                  </span>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        <?php else: ?>
          <p class="text-center text-gray-500">No hay productos disponibles para esta marca.</p>
        <?php endif; ?>
      </section>
    </div>
  </main>

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
</body>
</html>
