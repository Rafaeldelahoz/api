<?php
// Servicio para registrar un nuevo usuario

header('Content-Type: application/json');
require 'conexion.php';

// Obtener datos desde POST
$input = json_decode(file_get_contents("php://input"), true);
$usuario = $input['usuario'] ?? '';
$clave = $input['clave'] ?? '';

// Validaciones básicas
if (empty($usuario) || empty($clave)) {
    echo json_encode(["estado" => "error", "mensaje" => "Usuario y clave son obligatorios."]);
    exit();
}

// Verificar si ya existe el usuario
$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$stmt->execute([$usuario]);

if ($stmt->rowCount() > 0) {
    echo json_encode(["estado" => "error", "mensaje" => "El usuario ya existe."]);
    exit();
}

// Encriptar la clave
$clave_hash = password_hash($clave, PASSWORD_DEFAULT);

// Insertar nuevo usuario
$stmt = $conexion->prepare("INSERT INTO usuarios (usuario, clave) VALUES (?, ?)");
if ($stmt->execute([$usuario, $clave_hash])) {
    echo json_encode(["estado" => "ok", "mensaje" => "Registro exitoso."]);
} else {
    echo json_encode(["estado" => "error", "mensaje" => "Error al registrar."]);
}
?>
