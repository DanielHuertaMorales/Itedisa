<?php
require_once './includes/conexion.php';

$username = 'adminITE';
$passwordHash = password_hash('Dani123', PASSWORD_DEFAULT);

$stmt = $conexion->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $passwordHash);

if ($stmt->execute()) {
    echo "Usuario creado correctamente";
} else {
    echo "Error al crear usuario: " . $stmt->error;
}

$stmt->close();
$conexion->close();
