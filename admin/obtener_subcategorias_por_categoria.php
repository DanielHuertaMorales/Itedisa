<?php
require_once './includes/conexion.php';
header('Content-Type: application/json');

$categoria_id = isset($_GET['categoria_id']) ? intval($_GET['categoria_id']) : 0;

if (!$categoria_id) {
    echo json_encode([]);
    exit;
}

$stmt = $conexion->prepare("SELECT id, nombre FROM subcategorias WHERE categoria_id = ? ORDER BY nombre ASC");
$stmt->bind_param("i", $categoria_id);
$stmt->execute();
$result = $stmt->get_result();

$subcategorias = [];
while ($row = $result->fetch_assoc()) {
    $subcategorias[] = $row;
}

echo json_encode($subcategorias);
