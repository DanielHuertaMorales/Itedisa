<?php
include "includes/db.php";

$sql = "SELECT * FROM categorias";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Categorías de Productos</title>
  <link rel="stylesheet" href="css/custom.css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 pt-[120px]">
  <?php include 'menu.php'; ?>

  <main class="max-w-7xl mx-auto px-6 pb-16">
    <br>
    <h1 class="text-3xl font-bold mb-8 text-center">Categorías de Productos</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <?php while($cat = $result->fetch_assoc()): ?>
        <a href="productos_categoria.php?cat=<?php echo urlencode($cat['nombre']); ?>" 
          class="group block rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 bg-white">
          
          <!-- Imagen de la categoría -->
          <div class="h-40 flex items-center justify-center overflow-hidden bg-gray-100">
            <img src="assets/img/categorias/<?php echo htmlspecialchars($cat['imagen']); ?>" 
                alt="<?php echo htmlspecialchars($cat['nombre']); ?>" 
                class="w-full h-full object-cover group-hover:scale-110 transform transition duration-300" />
          </div>

          <div class="p-4 text-center">
            <h2 class="text-xl font-semibold text-gray-800 group-hover:text-blue-600 transition">
              <?php echo htmlspecialchars($cat['nombre']); ?>
            </h2>
          </div>
        </a>
      <?php endwhile; ?>
    </div>
  </main>

  <script src="js/main.js"></script>
</body>
</html>
