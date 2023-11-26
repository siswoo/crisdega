<?php
// Datos de conexión
$servidor = "localhost";
$usuario = "root"; // Reemplaza con tu nombre de usuario de MySQL
$contrasena = ""; // Reemplaza con tu contraseña de MySQL
$basededatos = "crisdega";

// Crear la conexión
$conexion = new mysqli($servidor, $usuario, $contrasena, $basededatos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error en la conexión a la base de datos: " . $conexion->connect_error);
}

// Establecer el conjunto de caracteres a UTF-8
if (!$conexion->set_charset("utf8")) {
    die("Error cargando el conjunto de caracteres utf8: " . $conexion->error);
}

// Ahora, puedes usar la variable $conexion para realizar consultas a la base de datos.
?>
