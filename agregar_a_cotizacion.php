<?php
session_start();
include "includes/db.php";

$id = $_POST['id'] ?? 0;
$id = intval($id);

if ($id <= 0) {
    echo json_encode(['success' => false, 'mensaje' => 'ID inválido']);
    exit;
}

// Obtener el producto
$stmt = $conn->prepare("SELECT id, nombre FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();

if (!$producto) {
    echo json_encode(['success' => false, 'mensaje' => 'Producto no encontrado']);
    exit;
}

// Inicializar cotización
if (!isset($_SESSION['cotizacion'])) {
    $_SESSION['cotizacion'] = [];
}

// Agregar o aumentar cantidad
$existe = false;
foreach ($_SESSION['cotizacion'] as &$p) {
    if ($p['id'] == $producto['id']) {
        $p['cantidad'] += 1;
        $existe = true;
        break;
    }
}
unset($p);

if (!$existe) {
    $_SESSION['cotizacion'][] = [
        'id' => $producto['id'],
        'nombre' => $producto['nombre'],
        'cantidad' => 1
    ];
}

echo json_encode(['success' => true]);
