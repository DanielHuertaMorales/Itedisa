<?php
include "includes/db.php";

$categoria = $_POST['categoria'] ?? '';
$marcas = $_POST['marcas'] ?? [];
$pagina = $_POST['pagina'] ?? 1;
$subcategoria = $_POST['subcategoria'] ?? '';

$pagina = intval($pagina);
$porPagina = 6;
$offset = ($pagina - 1) * $porPagina;

// Obtener id de categoría
$stmt = $conn->prepare("SELECT id FROM categorias WHERE nombre = ?");
$stmt->bind_param("s", $categoria);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$id_categoria = $row['id'] ?? 0;

if ($id_categoria === 0) {
    echo json_encode(['html' => '<p>Categoría no válida.</p>', 'paginacion' => '']);
    exit;
}

// Construir la consulta para contar total productos
$sqlCount = "SELECT COUNT(*) as total 
             FROM productos p
             JOIN subcategorias s ON p.id_subcategoria = s.id
             WHERE s.categoria_id = ?";

$params = [$id_categoria];
$types = "i";

if ($subcategoria !== '') {
    $sqlCount .= " AND p.id_subcategoria = ?";
    $params[] = $subcategoria;
    $types .= "i";
}

if (!empty($marcas)) {
    $placeholders = implode(',', array_fill(0, count($marcas), '?'));
    $sqlCount .= " AND p.id_marca IN ($placeholders)";
    $params = array_merge($params, $marcas);
    $types .= str_repeat("i", count($marcas));
}

$stmtCount = $conn->prepare($sqlCount);
$stmtCount->bind_param($types, ...$params);
$stmtCount->execute();
$total = $stmtCount->get_result()->fetch_assoc()['total'];
$totalPaginas = ceil($total / $porPagina);

// Construir consulta para obtener productos paginados
$sql = "SELECT p.*, m.nombre AS marca_nombre 
        FROM productos p 
        JOIN marcas m ON p.id_marca = m.id 
        JOIN subcategorias s ON p.id_subcategoria = s.id
        WHERE s.categoria_id = ?";

$params = [$id_categoria];
$types = "i";

if ($subcategoria !== '') {
    $sql .= " AND p.id_subcategoria = ?";
    $params[] = $subcategoria;
    $types .= "i";
}

if (!empty($marcas)) {
    $placeholders = implode(',', array_fill(0, count($marcas), '?'));
    $sql .= " AND p.id_marca IN ($placeholders)";
    $params = array_merge($params, $marcas);
    $types .= str_repeat("i", count($marcas));
}

$sql .= " ORDER BY p.nombre ASC LIMIT ? OFFSET ?";
$params[] = $porPagina;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$html = '';
if ($result->num_rows === 0) {
    $html = '<p class="text-gray-600 col-span-full">No se encontraron productos.</p>';
} else {
    while ($row = $result->fetch_assoc()) {
        $html .= '
        <div class="bg-white rounded-2xl shadow-md p-4 hover:shadow-xl transition duration-300 flex flex-col">
          <img src="assets/img/productos/' . htmlspecialchars($row['imagen']) . '" alt="' . htmlspecialchars($row['nombre']) . '" class="w-full h-40 object-contain mb-4 rounded-lg" />
          <h3 class="text-lg font-bold text-red-900">' . htmlspecialchars($row['nombre']) . '</h3>
          <p class="text-sm text-gray-500 mb-2">Marca: ' . htmlspecialchars($row['marca_nombre']) . '</p>
          <p class="text-gray-700 text-sm mb-4 line-clamp-3">' . htmlspecialchars($row['descripcion']) . '</p>
          <a href="producto_detalle.php?id=' . $row['id'] . '" 
            class="mt-auto inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-center">
            Ver más
          </a>
        </div>';
    }
}

// Paginación
$paginacion = '';
for ($i = 1; $i <= $totalPaginas; $i++) {
    $paginacion .= '<button class="paginacion-link px-3 py-1 mx-1 bg-red-100 hover:bg-red-300 rounded" data-page="' . $i . '">' . $i . '</button>';
}

echo json_encode(['html' => $html, 'paginacion' => $paginacion]);
