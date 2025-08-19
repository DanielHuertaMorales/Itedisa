<?php
include 'includes/db.php';

$id_marca = isset($_POST['id_marca']) ? intval($_POST['id_marca']) : 0;
$buscar = isset($_POST['buscar']) ? trim($_POST['buscar']) : '';
$limite = isset($_POST['limite']) ? intval($_POST['limite']) : 20;
$offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;

if ($id_marca === 0) {
  echo "Marca no válida.";
  exit;
}

$sql = "SELECT productos.id, productos.nombre, productos.imagen, categorias.nombre AS categoria 
        FROM productos
        JOIN subcategorias ON productos.id_subcategoria = subcategorias.id
        JOIN categorias ON subcategorias.categoria_id = categorias.id
        WHERE productos.id_marca = ?";

$params = [$id_marca];
$types = 'i';

if (!empty($buscar)) {
  $sql .= " AND productos.nombre LIKE ?";
  $types .= 's';
  $params[] = '%' . $buscar . '%';
}

$sql .= " ORDER BY productos.nombre ASC LIMIT ? OFFSET ?";
$types .= 'ii';
$params[] = $limite;
$params[] = $offset;

$stmt = $conn->prepare($sql);

if (!$stmt) {
  echo "Error en la preparación: " . $conn->error;
  exit;
}

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "<p class='text-gray-600 col-span-full'>No se encontraron productos.</p>";
}

while ($producto = $result->fetch_assoc()) {
    echo "
    <a href='producto_detalle.php?id=" . $producto['id'] . "' class='block'>
      <div class='bg-white rounded-2xl shadow-md p-4 hover:shadow-xl transition-all duration-300'>
        <img src='assets/img/productos/" . htmlspecialchars($producto['imagen']) . "' alt='" . htmlspecialchars($producto['nombre']) . "' class='w-full h-48 object-contain mb-4 rounded-lg'>
        <h3 class='text-md font-semibold text-gray-900'>" . htmlspecialchars($producto['nombre']) . "</h3>
        <span class='inline-block mt-2 px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded-full'>" . htmlspecialchars($producto['categoria']) . "</span>
      </div>
    </a>
    ";
}
?>
