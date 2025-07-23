<?php
include "includes/db.php";

$categoria = $_GET['cat'] ?? '';
$sql = "SELECT productos.*, marcas.nombre AS marca_nombre 
        FROM productos 
        JOIN categorias ON productos.id_categoria = categorias.id 
        JOIN marcas ON productos.id_marca = marcas.id 
        WHERE categorias.nombre = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $categoria);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Productos - <?php echo htmlspecialchars($categoria); ?></title>
  <link rel="stylesheet" href="css/custom.css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 pt-[120px]"> <!-- Cambié p-4 por pt-[120px] -->

    <?php include 'menu.php'; ?>

  <main class="px-4 pb-10"> <!-- Aquí agregas padding horizontal y abajo para el contenido -->
    <h1 class="text-2xl font-bold mb-4">Productos: <?php echo htmlspecialchars($categoria); ?></h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="bg-white rounded shadow p-4">
          <img src="assets/img/productos/<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>" class="w-full h-48 object-contain mb-4" />
          <h2 class="text-lg font-semibold"><?php echo $row['nombre']; ?></h2>
          <p class="text-sm text-gray-600 mb-2">Marca: <?php echo $row['marca_nombre']; ?></p>
          <p class="text-gray-700"><?php echo $row['descripcion']; ?></p>
        </div>
      <?php endwhile; ?>
    </div>
  </main>

  <script src="js/main.js"></script>
</body>
</html>
