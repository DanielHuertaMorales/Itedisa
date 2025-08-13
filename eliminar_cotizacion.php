<?php
session_start();

$id = $_POST['id'] ?? 0;
$id = intval($id);

if ($id <= 0) {
    echo json_encode(['success' => false, 'mensaje' => 'ID inválido']);
    exit;
}

if (!isset($_SESSION['cotizacion']) || empty($_SESSION['cotizacion'])) {
    echo json_encode(['success' => false, 'mensaje' => 'No hay productos en la cotización']);
    exit;
}

// Filtrar la cotización eliminando el producto seleccionado
$_SESSION['cotizacion'] = array_filter($_SESSION['cotizacion'], function($p) use ($id) {
    return $p['id'] != $id;
});

// Reindexar array
$_SESSION['cotizacion'] = array_values($_SESSION['cotizacion']);

// Si no queda ningún producto, la sesión puede reiniciarse a vacío
if (empty($_SESSION['cotizacion'])) {
    $_SESSION['cotizacion'] = [];
}

echo json_encode(['success' => true]);
