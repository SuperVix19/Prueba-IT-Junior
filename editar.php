<?php
include 'config.php';
include 'funciones.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("ID inválido.");
}

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Usuario no encontrado.");
    }
} catch (Exception $e) {
    error_log("Error al obtener usuario: " . $e->getMessage());
    die("Ocurrió un error al obtener el usuario.");
}

$nombreError = $apellidoError = $emailError = '';
$nombre = $usuario['nombre'];
$apellido = $usuario['apellido'];
$email = $usuario['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar Token CSRF
    validarTokenCSRF($_POST['csrf_token']);

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

    // Si no hay errores, actualizar en la base de datos
    if (empty($nombreError) && empty($apellidoError) && empty($emailError)) {
        try {
            $stmt = $conn->prepare("UPDATE users SET nombre = :nombre, apellido = :apellido, email = :email WHERE id = :id");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("Refresh: 0; url=index.php");
                exit();
            } else {
                echo "Error al actualizar el usuario.";
            }
        } catch (Exception $e) {
            error_log("Error al agregar usuario: " . $e->getMessage());
            if (strpos($e->getMessage(), 'Duplicate entry')!== false) {
                echo "<script type=\"text/javascript\"> alert(\"El email ya está registrado\");</script>";
            } else {
                echo "Ocurrió un error al agregar el usuario.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es_mx">
<head>
    <!-- Meta etiquetas y enlaces a CSS -->
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <!-- Enlaces a Bootstrap y tus estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <section id="editar">
        <div class="container-fluid sin-padding">
            <div class="seccion-formulario container-xl">
                <div class="titulo">
                    <h1>Editar Usuario</h1>
                </div>
                <form class="row g-3 needs-validation" novalidate method="POST" action="editar.php?id=<?= urlencode($id) ?>" id="formulario">
                    <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF() ?>">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>" required>
                        <div class="invalid-feedback">
                            Por favor, ingresa tu nombre.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" value="<?= htmlspecialchars($apellido, ENT_QUOTES, 'UTF-8') ?>" required>
                        <div class="invalid-feedback">
                            Por favor, ingresa tu apellido.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required>
                        <div class="invalid-feedback">
                            Por favor, ingresa un correo electrónico válido.
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Actualizar</button>
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js" integrity="sha512-2rNj2KJ+D8s1ceNasTIex6z4HWyOnEYLVC3FigGOmyQCZc2eBXKgOxQmo3oKLHyfcj53uz4QMsRCWNbLd32Q1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="assets/js/validaciones.js"></script>

</body>
</html>
