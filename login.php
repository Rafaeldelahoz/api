<?php
// Servicio para autenticar usuario

header('Content-Type: application/json');
require './conexion.php';

$input = json_decode(file_get_contents("php://input"), true);
$usuario = $input['usuario'] ?? '';
$clave = $input['clave'] ?? '';

if (empty($usuario) || empty($clave)) {
    echo json_encode(["estado" => "error", "mensaje" => "Usuario y clave requeridos."]);
    exit();
}

// Buscar usuario
$stmt = $conexion->prepare("SELECT clave FROM usuarios WHERE usuario = ?");
$stmt->execute([$usuario]);

if ($stmt->rowCount() == 0) {
    echo json_encode(["estado" => "error", "mensaje" => "Usuario no encontrado."]);
    exit();
}

$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar la contraseña
if (password_verify($clave, $row['clave'])) {
    echo json_encode(["estado" => "ok", "mensaje" => "Autenticación satisfactoria."]);
} else {
    echo json_encode(["estado" => "error", "mensaje" => "Error en autenticación."]);
}
?>

