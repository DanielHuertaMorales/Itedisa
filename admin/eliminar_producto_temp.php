<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'mensaje' => 'Acceso no válido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$index = $data['index'] ?? -1;

if (!isset($_SESSION['productos_temp'][$index])) {
    echo json_encode(['success' => false, 'mensaje' => 'Índice inválido']);
    exit;
}

array_splice($_SESSION['productos_temp'], $index, 1);
echo json_encode(['success' => true, 'mensaje' => 'Producto eliminado']);
exit;
