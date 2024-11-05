<?php
include 'config.php';
include 'funciones.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar Token CSRF
    validarTokenCSRF($_POST['csrf_token']);

    $nombreError = $apellidoError = $emailError = $passwordError = '';
    $nombre = $apellido = $email = $password = '';

    // Validaciones en el backend
    if (empty($_POST["nombre"])) {
        $nombreError = "El nombre es obligatorio";
    } else {
        $nombre = limpiarDatos($_POST["nombre"]);
    }

    if (empty($_POST["apellido"])) {
        $apellidoError = "El apellido es obligatorio";
    } else {
        $apellido = limpiarDatos($_POST["apellido"]);
    }

    if (empty($_POST["email"])) {
        $emailError = "El email es obligatorio";
    } else {
        $email = limpiarDatos($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = "Formato de email inválido";
        }
    }

    if (empty($_POST["password"])) {
        $passwordError = "La contraseña es obligatoria";
    } else {
        $password_plain = $_POST["password"];
        // Puedes agregar más validaciones de contraseña aquí
        $password = password_hash($password_plain, PASSWORD_DEFAULT);
    }

    // Si no hay errores, insertar en la base de datos
    if (empty($nombreError) && empty($apellidoError) && empty($emailError) && empty($passwordError)) {
        try {
            $stmt = $conn->prepare("INSERT INTO users (nombre, apellido, email, password) VALUES (:nombre, :apellido, :email, :password)");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                echo "Error al agregar el usuario.";
            }
        } catch (Exception $e) {
            error_log("Error al agregar usuario: " . $e->getMessage());
            if (strpos($e->getMessage(), 'Duplicate entry')!== false) {
                echo "<script type=\"text/javascript\"> alert(\"El email ya está registrado\"); </script>";
            } else {
                echo "<script type=\"text/javascript\"> alert(\"Ocurrió un error al agregar el usuario\"); </script>";
            }
            header("Refresh: 0; url=index.php");
        }
    } else {
        header("Refresh: 0; url=index.php");
        exit();
    }
} else {
    header("Refresh: 0; url=index.php");
    exit();
}
?>
