<?php
session_start();
require_once './includes/conexion.php';

header('Content-Type: application/json');

if (!isset($_SESSION['productos_temp']) || empty($_SESSION['productos_temp'])) {
    echo json_encode(['success' => false, 'mensaje' => 'No hay productos para guardar']);
    exit;
}

$productos = $_SESSION['productos_temp'];

$stmt = $conexion->prepare("INSERT INTO productos (nombre, descripcion, id_subcategoria, id_marca, caracteristicas, imagen, ficha_tecnica_url) VALUES (?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    echo json_encode(['success' => false, 'mensaje' => 'Error en preparación de consulta: ' . $conexion->error]);
    exit;
}

foreach ($productos as $prod) {
    $stmt->bind_param(
        "ssiisss",
        $prod['nombre'],
        $prod['descripcion'],
        $prod['subcategoria_id'],
        $prod['marca_id'],
        $prod['caracteristicas'],
        $prod['imagen'],
        $prod['ficha_tecnica_url']
    );
    $stmt->execute();
}

$stmt->close();
unset($_SESSION['productos_temp']);

echo json_encode(['success' => true, 'mensaje' => 'Todos los productos fueron guardados en la base de datos']);
exit;
