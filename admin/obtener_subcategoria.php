<?php
session_start();
require_once './includes/conexion.php';

header('Content-Type: application/json');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    echo json_encode(null);
    exit;
}

$stmt = $conexion->prepare("SELECT id, nombre, categoria_id FROM subcategorias WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $subcat = $res->fetch_assoc();
    echo json_encode($subcat);
} else {
    echo json_encode(null);
}

$stmt->close();
$conexion->close();
