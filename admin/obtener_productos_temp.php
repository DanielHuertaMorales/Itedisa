<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['productos_temp'])) {
    echo json_encode([]);
    exit;
}

echo json_encode($_SESSION['productos_temp']);
exit;
