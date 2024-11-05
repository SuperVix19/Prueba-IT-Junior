<?php
include 'config.php';
include 'funciones.php';
require 'vendor/autoload.php'; // Asegúrate de que el autoload de Composer está incluido

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Clave secreta para firmar el token JWT
$secret_key = "tu_clave_secreta";

// Obtener el token JWT del encabezado de autorización
$headers = getallheaders();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

if ($authHeader) {
    list($type, $token) = explode(" ", $authHeader, 2);

    if (strcasecmp($type, 'Bearer') == 0) {
        try {
            // Decodificar el token
            $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
            // Token válido
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Token inválido: ' . $e->getMessage()]);
            exit();
        }
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Tipo de autenticación no soportado']);
        exit();
    }
} else {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado, se requiere token Bearer']);
    exit();
}

// Configurar cabeceras
header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Obtener el ID si está presente en la URL
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

switch ($method) {
    case 'GET':
        try {
            if ($id) {
                // Obtener un usuario específico
                $stmt = $conn->prepare("SELECT id, nombre, apellido, email, fecha_registro FROM users WHERE id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($usuario) {
                    echo json_encode($usuario);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Usuario no encontrado']);
                }
            } else {
                // Obtener todos los usuarios
                $stmt = $conn->prepare("SELECT id, nombre, apellido, email, fecha_registro FROM users");
                $stmt->execute();
                $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($usuarios);
            }
        } catch (Exception $e) {
            error_log("Error en GET API: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error al procesar la solicitud']);
        }
        break;

    case 'POST':
        // Agregar un nuevo usuario
        $nombre = limpiarDatos($input['nombre'] ?? '');
        $apellido = limpiarDatos($input['apellido'] ?? '');
        $email = limpiarDatos($input['email'] ?? '');
        $password_plain = $input['password'] ?? '';

        // Validaciones
        $errores = [];

        if (empty($nombre)) {
            $errores[] = 'El nombre es obligatorio.';
        }
        if (empty($apellido)) {
            $errores[] = 'El apellido es obligatorio.';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El email es inválido.';
        }
        if (empty($password_plain)) {
            $errores[] = 'La contraseña es obligatoria.';
        }

        if (!empty($errores)) {
            http_response_code(400);
            echo json_encode(['error' => $errores]);
            exit();
        }

        $password = password_hash($password_plain, PASSWORD_DEFAULT);

        try {
            $stmt = $conn->prepare("INSERT INTO users (nombre, apellido, email, password) VALUES (:nombre, :apellido, :email, :password)");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);

            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode(['mensaje' => 'Usuario creado']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al crear el usuario']);
            }
        } catch (Exception $e) {
            error_log("Error en POST API: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error al procesar la solicitud']);
        }
        break;

    case 'PUT':
        // Actualizar un usuario existente
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Se requiere ID para actualizar']);
            exit();
        }

        $nombre = limpiarDatos($input['nombre'] ?? '');
        $apellido = limpiarDatos($input['apellido'] ?? '');
        $email = limpiarDatos($input['email'] ?? '');

        // Validaciones
        $errores = [];

        if (empty($nombre)) {
            $errores[] = 'El nombre es obligatorio.';
        }
        if (empty($apellido)) {
            $errores[] = 'El apellido es obligatorio.';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El email es inválido.';
        }

        if (!empty($errores)) {
            http_response_code(400);
            echo json_encode(['error' => $errores]);
            exit();
        }

        try {
            $stmt = $conn->prepare("UPDATE users SET nombre = :nombre, apellido = :apellido, email = :email WHERE id = :id");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['mensaje' => 'Usuario actualizado']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al actualizar el usuario']);
            }
        } catch (Exception $e) {
            error_log("Error en PUT API: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error al procesar la solicitud']);
        }
        break;

    case 'DELETE':
        // Eliminar un usuario
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Se requiere ID para eliminar']);
            exit();
        }

        try {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['mensaje' => 'Usuario eliminado']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al eliminar el usuario']);
            }
        } catch (Exception $e) {
            error_log("Error en DELETE API: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error al procesar la solicitud']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        break;
}
?>
