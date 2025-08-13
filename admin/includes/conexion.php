
<?php
$host = 'localhost';
$db = 'itedisa_bd';
$user = 'root';
$pass = '';
$conexion = new mysqli($host, $user, $pass, $db);
if ($conexion->connect_error) {
  die("Conexión fallida: " . $conexion->connect_error);
}
?>
