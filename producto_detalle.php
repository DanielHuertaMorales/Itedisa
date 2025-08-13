<?php
session_start();
include "includes/db.php";

$id = $_GET['id'] ?? 0;
$id = intval($id);

// Obtener detalles del producto
$stmt = $conn->prepare("
  SELECT p.*, m.nombre as marca_nombre, s.categoria_id
  FROM productos p 
  JOIN marcas m ON p.id_marca = m.id 
  JOIN subcategorias s ON p.id_subcategoria = s.id
  WHERE p.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();

if (!$producto) {
    echo "Producto no encontrado.";
    exit;
}

// Obtener recomendados
$categoria_id = $producto['categoria_id'];
$stmtRec = $conn->prepare("
  SELECT p.id, p.nombre, p.imagen, m.nombre as marca_nombre 
  FROM productos p 
  JOIN marcas m ON p.id_marca = m.id
  JOIN subcategorias s ON p.id_subcategoria = s.id
  WHERE s.categoria_id = ? AND p.id != ? 
  ORDER BY RAND() LIMIT 6
");
$stmtRec->bind_param("ii", $categoria_id, $id);
$stmtRec->execute();
$recomendados = $stmtRec->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($producto['nombre']); ?> - ITEDISA</title>
  <link rel="stylesheet" href="css/custom.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    /* Ocultar scrollbar en móviles */
    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen pt-[120px]">

  <?php include 'menu.php'; ?>

  <main class="flex flex-col lg:flex-row pt-10 px-6 pb-16 gap-8 flex-grow">
    <!-- Detalle producto -->
    <section class="w-full lg:w-3/4 bg-white rounded-2xl shadow-md p-6">
      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Imagen -->
        <div class="lg:w-1/2 flex justify-center items-center">
          <img src="assets/img/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" 
              alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
              class="w-full max-h-[400px] object-contain rounded-xl" />
        </div>

        <!-- Info principal -->
        <div class="lg:w-1/2 flex flex-col justify-center">
          <h1 class="text-3xl font-bold mb-6 text-red-900"><?php echo htmlspecialchars($producto['nombre']); ?></h1>
          
          <?php if (!empty($producto['descripcion'])): ?>
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Descripción</h2>
            <p class="text-gray-700 mb-6 whitespace-pre-line"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
          <?php endif; ?>

          <?php if (!empty($producto['caracteristicas'])): ?>
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Características</h2>
            <ul class="list-disc list-inside text-gray-700 mb-6">
              <?php foreach (explode("\n", $producto['caracteristicas']) as $caracteristica): ?>
                <?php if (trim($caracteristica)) : ?>
                  <li><?php echo htmlspecialchars(trim($caracteristica)); ?></li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <div class="flex space-x-4 mt-6">
              <?php if (!empty($producto['ficha_tecnica_url'])): ?>
                  <a href="<?php echo htmlspecialchars($producto['ficha_tecnica_url']); ?>" 
                      target="_blank" 
                      class="inline-block px-5 py-2 text-white bg-red-600 rounded-lg shadow hover:bg-red-700 hover:scale-105 transform transition duration-300">
                      📄 Ver ficha técnica
                  </a>
              <?php endif; ?>

              <!-- Botón Cotizar -->
              <button 
                  onclick="agregarACotizacion(this, <?php echo $producto['id']; ?>)" 
                  class="px-5 py-3 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition-all duration-300 transform hover:scale-110 animate-bounce-once">
                  💰 Cotizar
              </button>
          </div>

        </div>
      </div>
    </section>


    <!-- Productos recomendados - Escritorio -->
    <aside class="hidden lg:block lg:w-96 bg-white rounded-xl shadow-md p-4 self-start sticky top-28 max-h-[calc(100vh-150px)] overflow-y-auto">
      <h2 class="text-xl font-semibold mb-4 text-red-900">Productos Recomendados</h2>
      <?php mysqli_data_seek($recomendados, 0); while ($rec = $recomendados->fetch_assoc()) : ?>
        <div onclick="location.href='producto_detalle.php?id=<?php echo $rec['id']; ?>'" class="flex flex-col rounded-lg overflow-hidden shadow hover:shadow-lg transition mb-4 cursor-pointer">
          <img src="assets/img/productos/<?php echo htmlspecialchars($rec['imagen']); ?>" alt="<?php echo htmlspecialchars($rec['nombre']); ?>" class="w-full h-32 object-contain bg-gray-100" />
          <div class="p-3">
            <h3 class="text-lg font-semibold text-blue-900 truncate"><?php echo htmlspecialchars($rec['nombre']); ?></h3>
            <p class="text-sm text-gray-600 truncate"><?php echo htmlspecialchars($rec['marca_nombre']); ?></p>
          </div>
        </div>
      <?php endwhile; ?>
    </aside>
  </main>

  <!-- Productos recomendados - Móvil -->
  <section class="lg:hidden bg-white shadow-md rounded-xl mt-6 mx-6 p-4">
    <h2 class="text-xl font-semibold mb-4 text-red-900">Productos Recomendados</h2>
    <div class="flex gap-4 overflow-x-auto scrollbar-hide pb-2">
      <?php mysqli_data_seek($recomendados, 0); while ($rec = $recomendados->fetch_assoc()) : ?>
        <div onclick="location.href='producto_detalle.php?id=<?php echo $rec['id']; ?>'" 
             class="min-w-[200px] bg-gray-50 flex-shrink-0 rounded-lg overflow-hidden shadow hover:shadow-lg transition cursor-pointer">
          <img src="assets/img/productos/<?php echo htmlspecialchars($rec['imagen']); ?>" alt="<?php echo htmlspecialchars($rec['nombre']); ?>" class="w-full h-32 object-contain bg-gray-100" />
          <div class="p-3">
            <h3 class="text-md font-semibold text-blue-900 truncate"><?php echo htmlspecialchars($rec['nombre']); ?></h3>
            <p class="text-sm text-gray-600 truncate"><?php echo htmlspecialchars($rec['marca_nombre']); ?></p>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

  <footer class="bg-gray-900 text-gray-300 py-10 px-4 sm:px-6 mt-auto">
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

<!-- Script para agregar producto a cotizar -->
<script>
function agregarACotizacion(btn, idProducto) {
    fetch('agregar_a_cotizacion.php', {
        method: 'POST',
        body: new URLSearchParams({ id: idProducto })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Crear badge flotante
            const badge = document.createElement('span');
            badge.textContent = "+1 Agregado";
            badge.className = "absolute top-0 left-1/2 -translate-x-1/2 -translate-y-full text-white bg-green-600 px-3 py-1 rounded-full text-sm font-bold animate-fade-up";

            // Asegurar que el botón tenga position relative
            btn.style.position = "relative";

            // Agregar badge al botón
            btn.appendChild(badge);

            // Remover badge después de 1.2s
            setTimeout(() => badge.remove(), 1200);

            // Cambiar color temporal del botón
            btn.classList.add('bg-green-800');
            setTimeout(() => btn.classList.remove('bg-green-800'), 500);
        } else {
            alert("Error al agregar el producto a cotización");
        }
    })
    .catch(err => console.error(err));
}
</script>


<!-- Estilo para animación bounce única -->
<style>
@keyframes fade-up {
    0% { transform: translateY(0); opacity: 1; }
    100% { transform: translateY(-20px); opacity: 0; }
}

.animate-fade-up {
    animation: fade-up 1.2s ease forwards;
}
</style>
