<?php
session_start();
header('Content-Type: application/json');

// Obtener el índice del producto desde GET
$index = isset($_GET['index']) ? intval($_GET['index']) : -1;

// Verificar si el índice existe en la sesión
if (!isset($_SESSION['productos_temp'][$index])) {
    http_response_code(404); // Opcional: código HTTP para indicar que no se encontró
    echo json_encode(null);
    exit;
}

// Retornar el producto encontrado en JSON
echo json_encode($_SESSION['productos_temp'][$index]);
exit;
?>
