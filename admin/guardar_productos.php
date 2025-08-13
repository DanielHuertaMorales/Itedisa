<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once './includes/conexion.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['productos']) || !is_array($data['productos'])) {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}

$productos = $data['productos'];
$guardados = 0;
$fallidos = [];

$stmt = $conexion->prepare(
    "INSERT INTO productos (nombre, descripcion, imagen, categoria_id, marca_id, caracteristicas, ficha_tecnica) VALUES (?, ?, ?, ?, ?, ?, ?)"
);

foreach ($productos as $index => $p) {
    try {
        $stmt->bind_param(
            "sssisss",
            $p['nombre'],
            $p['descripcion'],
            $p['imagen'],
            $p['categoria_id'],
            $p['marca_id'],
            $p['caracteristicas'],
            $p['ficha_tecnica']
        );
        if ($stmt->execute()) {
            $guardados++;
        } else {
            $fallidos[] = [
                'producto' => $p['nombre'],
                'error' => $stmt->error
            ];
        }
    } catch (Exception $e) {
        $fallidos[] = [
            'producto' => $p['nombre'],
            'error' => $e->getMessage()
        ];
    }
}

echo json_encode([
    'success' => $guardados > 0,
    'guardados' => $guardados,
    'fallidos' => $fallidos
]);
