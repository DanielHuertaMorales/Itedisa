<?php
include "includes/db.php";

$categoria = $_POST['categoria'] ?? '';
$marcas = $_POST['marcas'] ?? [];
$pagina = $_POST['pagina'] ?? 1;
$porPagina = 6;
$offset = ($pagina - 1) * $porPagina;

// Filtrar por ID de categoría
$stmt = $conn->prepare("SELECT id FROM categorias WHERE nombre = ?");
$stmt->bind_param("s", $categoria);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$id_categoria = $row['id'] ?? 0;

// Contar total de productos
$sqlCount = "SELECT COUNT(*) as total FROM productos WHERE id_categoria = ?";
$params = [$id_categoria];
$types = "i";

if (!empty($marcas)) {
  $sqlCount .= " AND id_marca IN (" . implode(',', array_fill(0, count($marcas), '?')) . ")";
  $params = array_merge([$id_categoria], $marcas);
  $types .= str_repeat("i", count($marcas));
}

$stmt = $conn->prepare($sqlCount);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
$totalPaginas = ceil($total / $porPagina);

// Obtener productos paginados
$sql = "SELECT productos.*, marcas.nombre AS marca_nombre 
        FROM productos 
        JOIN marcas ON productos.id_marca = marcas.id 
        WHERE productos.id_categoria = ?";
$params = [$id_categoria];
$types = "i";

if (!empty($marcas)) {
  $sql .= " AND productos.id_marca IN (" . implode(',', array_fill(0, count($marcas), '?')) . ")";
  $params = array_merge([$id_categoria], $marcas);
  $types .= str_repeat("i", count($marcas));
}

$sql .= " LIMIT ? OFFSET ?";
$params[] = $porPagina;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$html = '';
while ($row = $result->fetch_assoc()) {
  $html .= '
    <div class="bg-white rounded shadow p-4 flex flex-col">
      <img src="assets/img/productos/' . $row['imagen'] . '" alt="' . $row['nombre'] . '" class="w-full h-40 object-contain mb-4" />
      <h3 class="text-lg font-bold text-blue-900">' . $row['nombre'] . '</h3>
      <p class="text-sm text-gray-500">Marca: ' . $row['marca_nombre'] . '</p>
      <p class="text-gray-700 mt-2 text-sm">' . $row['descripcion'] . '</p>
    </div>';
}

// Paginación
$paginacion = '';
for ($i = 1; $i <= $totalPaginas; $i++) {
  $paginacion .= '<button class="paginacion-link px-3 py-1 mx-1 bg-blue-100 hover:bg-blue-300 rounded" data-page="' . $i . '">' . $i . '</button>';
}

echo json_encode(['html' => $html, 'paginacion' => $paginacion]);
