<?php
session_start();
require_once './includes/conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if (!$id) {
    echo json_encode(['success' => false, 'mensaje' => 'ID inválido']);
    exit;
}

// Borrar subcategoría
$stmt = $conexion->prepare("DELETE FROM subcategorias WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'mensaje' => 'Subcategoría eliminada']);
} else {
    echo json_encode(['success' => false, 'mensaje' => 'Error al eliminar']);
}

$stmt->close();
$conexion->close();
