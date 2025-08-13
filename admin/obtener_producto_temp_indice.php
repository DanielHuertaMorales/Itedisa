<?php
session_start();
header('Content-Type: application/json');

$index = isset($_GET['index']) ? intval($_GET['index']) : -1;

if (!isset($_SESSION['productos_temp'][$index])) {
    echo json_encode(null);
    exit;
}

echo json_encode($_SESSION['productos_temp'][$index]);
